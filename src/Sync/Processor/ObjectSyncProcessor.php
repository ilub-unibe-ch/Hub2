<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;
use ilObject;
use ilObjOrgUnit;
use ilObjUser;

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Exception\ILIASObjectNotFoundException;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use Throwable;
use ilMailMimeSenderFactory;

/**
 * Class ObjectProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ObjectSyncProcessor implements IObjectSyncProcessor
{
    use Helper;

    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected \ilLanguage $lng;
    protected ilMailMimeSenderFactory $sender_factory;
    protected \ilObjectDataCache $object_data_cache;
    protected \ilSetting $settings;
    protected ilObjUser $user;
    protected \ilObjectDefinition $object_definition;
    protected \ilDBInterface $database;
    protected \ilTree $tree;
    protected \ilRbacAdmin $rbac_admin;

    /**
     * @var IOrigin
     */
    protected IOrigin $origin;
    /**
     * @var IObjectStatusTransition
     */
    protected IObjectStatusTransition $transition;
    /**
     * @var IOriginImplementation
     */
    protected IOriginImplementation $implementation;
    /**
     * @var ilObject|FakeIliasObject|null
     */
    protected $current_ilias_object = null;

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $transition
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        global $DIC;
        $this->tree = $DIC->repositoryTree();
        $this->rbac_admin = $DIC->rbac()->admin();
        $this->database = $DIC->database();
        $this->object_definition = $DIC['objDefinition'];
        $this->user = $DIC->user();
        $this->settings = $DIC->settings();
        $this->object_data_cache = $DIC['ilObjDataCache'];
        $this->sender_factory = $DIC['mail.mime.sender.factory'];
        $this->lng = $DIC->language();

        $this->origin = $origin;
        $this->transition = $transition;
        $this->implementation = $implementation;
    }

    /**
     * @inheritdoc
     */
    final public function process(IObject $object, IDataTransferObject $dto, bool $force = false)
    {
        // The HookObject is filled with the object (known Data in HUB) and the DTO delivered with
        // your origin. Additionally, if available, the HookObject is filled with the given
        // ILIAS-Object, too.
        $hook = new HookObject($object, $dto);

        // We pass the HookObject to the OriginImplementaion which could override the status
        $this->implementation->overrideStatus($hook);

        // We keep the old data if the object is getting deleted, as there is no "real" DTO available, because
        // the data has not been delivered...

        // We check if there is another mapping strategy than "None" and check for existing objects in ILIAS
        if ($object->getStatus() === IObject::STATUS_TO_CREATE && $dto instanceof IMappingStrategyAwareDataTransferObject) {
            $m = $dto->getMappingStrategy();
            $ilias_id = $m->map($dto);
            if ($ilias_id > 0) {
                $object->setStatus(IObject::STATUS_TO_UPDATE);
                $object->setILIASId((string)$ilias_id);
            } elseif ($ilias_id < 0) {
                throw new HubException("Mapping strategy " . get_class($m) . " returns negative value");
            }
            $object->store();
        }

        if ($object->getStatus() !== IObject::STATUS_TO_OUTDATED) {
            $object->setData($dto->getData());
            if ($dto instanceof IMetadataAwareDataTransferObject
                && $object instanceof IMetadataAwareObject) {
                $object->setMetaData($dto->getMetaData());
            }
            if ($dto instanceof ITaxonomyAwareDataTransferObject
                && $object instanceof ITaxonomyAwareObject) {
                $object->setTaxonomies($dto->getTaxonomies());
            }
        }

        $time = time();

        $this->current_ilias_object = null;

        switch ($object->getStatus()) {
            case IObject::STATUS_TO_CREATE:
                $this->implementation->beforeCreateILIASObject($hook);

                try {
                    $this->handleCreate($dto);
                } catch (Throwable $ex) {
                    // Store new possible ilias id on exception
                    $object->setILIASId((string)$this->getILIASId($this->current_ilias_object));

                    throw $ex;
                }

                if ($this instanceof IMetadataSyncProcessor) {
                    $this->handleMetadata($dto, $this->current_ilias_object);
                }

                if ($this instanceof ITaxonomySyncProcessor) {
                    $this->handleTaxonomies($dto, $this->current_ilias_object);
                }

                $object->setILIASId($this->getILIASId($this->current_ilias_object));

                $this->implementation->afterCreateILIASObject($hook->withILIASObject($this->current_ilias_object));

                $object->setStatus(IObject::STATUS_CREATED);
                $object->setProcessedDate($time);
                break;

            case IObject::STATUS_TO_UPDATE:
            case IObject::STATUS_TO_RESTORE:
                // Updating the ILIAS object is only needed if some properties were changed
                if (($object->computeHashCode() != $object->getHashCode()) || $force || $object->getStatus() === IObject::STATUS_TO_RESTORE) {
                    $this->implementation->beforeUpdateILIASObject($hook);

                    try {
                        $this->handleUpdate($dto, $object->getILIASId());
                    } catch (Throwable $ex) {
                        // Store new possible ilias id on exception
                        $object->setILIASId($this->getILIASId($this->current_ilias_object));

                        throw $ex;
                    }

                    if ($this->current_ilias_object === null) {
                        throw new ILIASObjectNotFoundException($object);
                    }

                    if ($this instanceof IMetadataSyncProcessor) {
                        $this->handleMetadata($dto, $this->current_ilias_object);
                    }

                    if ($this instanceof ITaxonomySyncProcessor) {
                        $this->handleTaxonomies($dto, $this->current_ilias_object);
                    }

                    $object->setILIASId($this->getILIASId($this->current_ilias_object));

                    $this->implementation->afterUpdateILIASObject($hook->withILIASObject($this->current_ilias_object));

                    $object->setStatus(IObject::STATUS_UPDATED);
                    $object->setProcessedDate($time);
                } else {
                    $object->setStatus(IObject::STATUS_IGNORED);
                }
                break;

            case IObject::STATUS_TO_OUTDATED:
                $this->implementation->beforeDeleteILIASObject($hook);

                $this->handleDelete($object->getILIASId());

                if ($this->current_ilias_object === null) {
                    throw new ILIASObjectNotFoundException($object);
                }

                $this->implementation->afterDeleteILIASObject($hook->withILIASObject($this->current_ilias_object));

                $object->setStatus(IObject::STATUS_OUTDATED);
                $object->setProcessedDate($time);
                break;

            case IObject::STATUS_IGNORED:
            case IObject::STATUS_FAILED:
                // Nothing to do here, object is ignored
                break;

            default:
                throw new HubException("Unrecognized intermediate status '{$object->getStatus()}' while processing $object");
        }

        $object->store();
    }

    /**
     * @param ilObject|FakeIliasObject|null $object
     * @return int|null
     */
    protected function getILIASId($object): ?string
    {
        if ($object === null) {
            return null;
        }

        if ($object instanceof ilObjUser || $object instanceof ilObjOrgUnit || $object instanceof FakeIliasObject) {
            return (string)$object->getId();
        }

        return (string)$object->getRefId();
    }

    /**
     * The import ID is set on the ILIAS object.
     * @param IDataTransferObject $object
     * @return string
     */
    protected function getImportId(IDataTransferObject $object): string
    {
        $import_id = self::IMPORT_PREFIX . $this->origin->getId() . '_' . $object->getExtId();
        return substr($import_id, 0, 50);
    }

    /**
     * @inheritdoc
     */
    public function handleSort(array $sort_dtos): bool
    {
        return false;
    }

    /**
     * Create a new ILIAS object from the given data transfer object.
     * @param IDataTransferObject $dto
     * @return void
     * @throws HubException
     */
    abstract protected function handleCreate(IDataTransferObject $dto)/*: void*/
    ;

    /**
     * Update the corresponding ILIAS object.
     * Return the processed ILIAS object or null if the object was not found, e.g. it is deleted in
     * ILIAS.
     * @param IDataTransferObject $dto
     * @param string                 $ilias_id
     * @return void
     * @throws HubException
     */
    abstract protected function handleUpdate(IDataTransferObject $dto, string $ilias_id)/*: void*/
    ;

    /**
     * Delete the corresponding ILIAS object.
     * Return the deleted ILIAS object or null if the object was not found in ILIAS.
     * @param string $ilias_id
     * @return void
     * @throws HubException
     */
    abstract protected function handleDelete(string $ilias_id)/*: void*/
    ;
}
