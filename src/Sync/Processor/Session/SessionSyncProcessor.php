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

namespace srag\Plugins\Hub2\Sync\Processor\Session;

use ilDateTime;
use ilObject2;
use ilObjSession;
use ilSessionAppointment;
use ilRepUtil;
use ilMD;
use ilMDLanguageItem;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Object\Session\ISessionDTO;
use srag\Plugins\Hub2\Origin\Properties\Session\ISessionProperties;

/**
 * Class SessionSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionSyncProcessor extends ObjectSyncProcessor implements ISessionSyncProcessor
{
    use MetadataSyncProcessor;
    use TaxonomySyncProcessor;

    /**
     * @var SessionProperties
     */
    private $props;
    /**
     * @var SessionOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static array $properties = [
        "title",
        "description",
        "location",
        "details",
        "name",
        "phone",
        "email",
        "registrationType",
        "registrationMinUsers",
        "registrationMaxUsers",
        "registrationWaitingList",
        "waitingListAutoFill",
        'showMembers'
    ];

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
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    /**
     * @return array
     */
    public static function getProperties(): array
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param SessionDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = new ilObjSession();
        $ilObjSession->setImportId($this->getImportId($dto));

        // Properties
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjSession->$setter($dto->$getter());
            }
        }
        if ($dto->getCannotParticipateOption() !== null) {
            $ilObjSession->enableCannotParticipateOption((bool) $dto->getCannotParticipateOption());
        }

        /**
         * Dates for first appointment need to be fixed before create since create raises
         * create prepareCalendarAppointments by ilAppEventHandler. At this point the
         * correct dates need to be set, otherwise, the current date will be used.
         **/
        $ilObjSession = $this->setDataForFirstAppointment($dto, $ilObjSession, true);
        $ilObjSession->create();
        $ilObjSession->createReference();
        $a_parent_ref = $this->buildParentRefId($dto);
        $ilObjSession->putInTree($a_parent_ref);
        $ilObjSession->setPermissions($a_parent_ref);
        /**
         * Since the id is only known after create, it has to be set again before
         * creation of the firs appointment, otherwise no event_appointment will be
         * generated for the session.
         */
        $this->setLanguage($dto, $ilObjSession);
        $ilObjSession->getFirstAppointment()->setSessionId($ilObjSession->getId());
        $ilObjSession->getFirstAppointment()->create();
    }

    /**
     * @inheritdoc
     * @param SessionDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = $this->findILIASObject((int)$ilias_id);
        if ($ilObjSession === null) {
            return;
        }

        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjSession->$setter($dto->$getter());
            }
        }

        if ($this->props->get(ISessionProperties::MOVE_SESSION)) {
            $this->moveSession($ilObjSession, $dto);
        }
        $this->setLanguage($dto, $ilObjSession);
        $ilObjSession = $this->setDataForFirstAppointment($dto, $ilObjSession, true);
        $ilObjSession->update();
        $ilObjSession->getFirstAppointment()->update();
    }

    /**
     * @inheritdoc
     */
    protected function handleDelete(string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjSession = $this->findILIASObject((int)$ilias_id);
        if ($ilObjSession === null) {
            return;
        }

        if ($this->props->get(ISessionProperties::DELETE_MODE) == ISessionProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(ISessionProperties::DELETE_MODE)) {
            case ISessionProperties::DELETE_MODE_DELETE:
                $ilObjSession->delete();
                break;
            case ISessionProperties::DELETE_MODE_MOVE_TO_TRASH:
                $this->tree->moveToTrash($ilObjSession->getRefId(), true);
                break;
        }
    }

    /**
     * @param int $ilias_id
     * @return ilObjSession|null
     */
    protected function findILIASObject(int $ilias_id): ?ilObjSession
    {
        if (!ilObject2::_exists($ilias_id, true)) {
            return null;
        }

        return new ilObjSession($ilias_id);
    }

    /**
     * @param SessionDTO $session
     * @return int
     * @throws HubException
     */
    protected function buildParentRefId(SessionDTO $session): int
    {
        if ($session->getParentIdType() == ISessionDTO::PARENT_ID_TYPE_REF_ID) {
            if ($this->tree->isInTree((int)$session->getParentId())) {
                return (int) $session->getParentId();
            }
        }
        if ($session->getParentIdType() == ISessionDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category.
            // We must search the parent ref-ID from a category object synced by a linked origin.
            // --> Get an instance of the linked origin and lookup the category by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if (!$linkedOriginId) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $possible_parents = array_merge($originRepository->groups(), $originRepository->courses());
            $array = array_filter($possible_parents, function ($origin) use ($linkedOriginId) {
                /** @var IOrigin $origin */
                return $origin->getId() == $linkedOriginId;
            });
            $origin = array_pop($array);
            if ($origin === null) {
                $msg = "The linked origin syncing courses or groups was not found, please check that the correct origin is linked";
                throw new HubException($msg);
            }
            $objectFactory = new ObjectFactory($origin);

            if ($origin instanceof ARCourseOrigin) {
                $parent = $objectFactory->course($session->getParentId());
            } else {
                $parent = $objectFactory->group($session->getParentId());
            }

            if (!$parent->getILIASId()) {
                throw new HubException("The linked course or group does not (yet) exist in ILIAS");
            }
            if (!$this->tree->isInTree((int)$parent->getILIASId())) {
                throw new HubException("Could not find the ref-ID of the parent course or group in the tree: '{$parent->getILIASId()}'");
            }

            return (int) $parent->getILIASId();
        }

        return 0;
    }

    /**
     * @param SessionDTO   $object
     * @param ilObjSession $ilObjSession
     * @param bool         $force
     * @return ilObjSession
     */
    protected function setDataForFirstAppointment(SessionDTO $object, ilObjSession $ilObjSession, bool $force = false): ilObjSession
    {
        /**
         * @var ilSessionAppointment $first
         */
        $appointments = $ilObjSession->getAppointments();
        $first = $ilObjSession->getFirstAppointment();
        if ($this->props->updateDTOProperty('start') || $force) {
            $start = new ilDateTime($object->getStart(), IL_CAL_UNIX);
            $first->setStart($start);
            $first->setStartingTime($start->get(IL_CAL_UNIX));
        }
        if ($this->props->updateDTOProperty('end') || $force) {
            $end = new ilDateTime($object->getEnd(), IL_CAL_UNIX);
            $first->setEnd($end);
            $first->setEndingTime($end->get(IL_CAL_UNIX));
        }
        if ($this->props->updateDTOProperty('fullDay') || $force) {
            $first->toggleFullTime($object->isFullDay());
        }
        $first->setSessionId($ilObjSession->getId());
        $appointments[0] = $first;
        $ilObjSession->setAppointments($appointments);

        return $ilObjSession;
    }

    /**
     * @param ilObjSession $ilObjSession
     * @param SessionDTO   $session
     * @throws HubException
     * @throws \ilRepositoryException
     */
    protected function moveSession(ilObjSession $ilObjSession, SessionDTO $session)
    {
        $parentRefId = $this->buildParentRefId($session);

        if ($this->tree->isDeleted($ilObjSession->getRefId())) {
            $ilRepUtil = new ilRepUtil();
            $ilRepUtil->restoreObjects($parentRefId, [$ilObjSession->getRefId()]);
        }
        $oldParentRefId = $this->tree->getParentId($ilObjSession->getRefId());
        if ($oldParentRefId == $parentRefId) {
            return;
        }
        $this->tree->moveTree($ilObjSession->getRefId(), $parentRefId);
        $this->rbac_admin->adjustMovedObjectPermissions($ilObjSession->getRefId(), $oldParentRefId);
    }

    /**
     * @param SessionDTO   $dto
     * @param ilObjSession $ilObjSession
     */
    protected function setLanguage(SessionDTO $dto, ilObjSession $ilObjSession)
    {
        $md_general = (new ilMD($ilObjSession->getId()))->getGeneral();
        //Note: this is terribly stupid, but the best (only) way if found to get to the
        //lang id of the primary language of some object. There seems to be multy lng
        //support however, not through the GUI. Maybe there is some bug in the generation
        //of the respective metadata form. See: initQuickEditForm() in ilMDEditorGUI
        $array = $md_general->getLanguageIds();
        $language = $md_general->getLanguage(array_pop($array));
        $new_language = $dto->getLanguageCode();
        if ($language->getLanguageCode() != $new_language) {
            $language->setLanguage(new ilMDLanguageItem($new_language));
            $language->update();
        }
    }
}
