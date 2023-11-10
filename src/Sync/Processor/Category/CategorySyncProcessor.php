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

namespace srag\Plugins\Hub2\Sync\Processor\Category;

use ilObjCategory;
use ilObjectServiceSettingsGUI;
use ilRepUtil;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Object\Category\ICategoryDTO;
use srag\Plugins\Hub2\Origin\Properties\Course\ICourseProperties;
use srag\Plugins\Hub2\Origin\Properties\Category\ICategoryProperties;

/**
 * Class CategorySyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategorySyncProcessor extends ObjectSyncProcessor implements ICategorySyncProcessor
{
    use MetadataSyncProcessor;
    use TaxonomySyncProcessor;

    /**
     * @var CategoryProperties
     */
    protected $props;
    /**
     * @var CategoryOriginConfig
     */
    protected $config;
    /**
     * @var array
     */
    protected static array $properties = [
        'title',
        'description',
        'owner',
        'orderType',
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
     * @param CategoryDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = new ilObjCategory();
        $ilObjCategory->setImportId($this->getImportId($dto));
        // Find the refId under which this course should be created
        $parentRefId = $this->determineParentRefId($dto);

        $ilObjCategory->create();
        $ilObjCategory->createReference();
        $ilObjCategory->putInTree((int) $parentRefId);
        $ilObjCategory->setPermissions((int) $parentRefId);
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjCategory->$setter($dto->$getter());
            }
        }
        if ($this->props->get(ICategoryProperties::SHOW_NEWS)) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                (string) $dto->isShowNews()
            );
        }
        if ($this->props->get(ICategoryProperties::SHOW_INFO_TAB)) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY,
                (string) $dto->isShowInfoPage()
            );
        }
        $ilObjCategory->update();

        $ilObjCategory->removeTranslations();
        $ilObjCategory->addTranslation(
            $dto->getTitle(),
            $dto->getDescription(),
            $this->lng->getDefaultLanguage(),
            "1"
        );
    }

    /**
     * @inheritdoc
     * @param CategoryDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = $this->findILIASCategory($ilias_id);
        if ($ilObjCategory === null) {
            return;
        }
        // Update some properties if they should be updated depending on the origin config
        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjCategory->$setter($dto->$getter());
            }
        }
        if ($this->props->updateDTOProperty('title')) {
            $ilObjCategory->removeTranslations();
            $ilObjCategory->addTranslation(
                $dto->getTitle(),
                $dto->getDescription(),
                $this->lng->getDefaultLanguage(),
                "1"
            );
        }
        if ($this->props->updateDTOProperty('showNews')) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::NEWS_VISIBILITY,
                (string) $dto->isShowNews()
            );
        }
        if ($this->props->updateDTOProperty('showInfoPage')) {
            ilObjCategory::_writeContainerSetting(
                $ilObjCategory->getId(),
                ilObjectServiceSettingsGUI::INFO_TAB_VISIBILITY,
                (string) $dto->isShowInfoPage()
            );
        }
        if ($this->props->get(ICategoryProperties::MOVE_CATEGORY)) {
            $this->moveCategory($ilObjCategory, $dto);
        }
    }

    /**
     * @inheritdoc
     */
    protected function handleDelete(string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjCategory = $this->findILIASCategory($ilias_id);
        if ($ilObjCategory === null) {
            return;
        }
        if ($this->props->get(ICategoryProperties::DELETE_MODE) == ICategoryProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(ICategoryProperties::DELETE_MODE)) {
            case ICategoryProperties::DELETE_MODE_MARK:
                $ilObjCategory->setTitle($ilObjCategory->getTitle() . ' ' . $this->props->get(ICategoryProperties::DELETE_MODE_MARK_TEXT));
                $ilObjCategory->update();
                break;
            case ICourseProperties::DELETE_MODE_DELETE:
                $ilObjCategory->delete();
                break;
        }
    }

    /**
     * @param CategoryDTO $category
     * @return string
     * @throws HubException
     */
    protected function determineParentRefId(CategoryDTO $category): int
    {
        if ($category->getParentIdType() == ICategoryDTO::PARENT_ID_TYPE_REF_ID) {
            if ($this->tree->isInTree((int)$category->getParentId())) {
                return (int)$category->getParentId();
            }
            // The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
            $parentRefId = $this->config->getParentRefIdIfNoParentIdFound();
            if (!$this->tree->isInTree($parentRefId)) {
                throw new HubException("Could not find the fallback parent ref-ID in tree: '{$parentRefId}'");
            }

            return $parentRefId;
        }
        if ($category->getParentIdType() == ICategoryDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category of the same origin.
            // We must search the category and check if its ILIAS ID does exist.
            $objectFactory = new ObjectFactory($this->origin);
            $parentCategory = $objectFactory->category($category->getParentId());
            if (!$parentCategory->getILIASId()) {
                // The given parent-ID does not yet exist, we check if we find the fallback category
                $fallbackExtId = $this->config->getExternalParentIdIfNoParentIdFound();
                $parentCategory = $objectFactory->category((string) $fallbackExtId);
                if (!$parentCategory->getILIASId()) {
                    throw new HubException("The linked category does not (yet) exist in ILIAS");
                }
            }

            return (int)$parentCategory->getILIASId();
        }

        return 0;
    }

    /**
     * @param int $iliasId
     * @return ilObjCategory|null
     */
    protected function findILIASCategory(string $iliasId): ?ilObjCategory
    {
        if (!ilObjCategory::_exists((int)$iliasId, true)) {
            return null;
        }

        return new ilObjCategory((int)$iliasId);
    }

    /**
     * Move the category to a new parent.
     * @param ilObjCategory $ilObjCategory
     * @param CategoryDTO   $category
     */
    protected function moveCategory(ilObjCategory $ilObjCategory, CategoryDTO $category)
    {
        $parentRefId = $this->determineParentRefId($category);
        if ($this->tree->isDeleted($ilObjCategory->getRefId())) {
            $ilRepUtil = new ilRepUtil();
            $ilRepUtil->restoreObjects($parentRefId, [$ilObjCategory->getRefId()]);
        }
        $oldParentRefId = $this->tree->getParentId($ilObjCategory->getRefId());
        if ($oldParentRefId == $parentRefId) {
            return;
        }
        $this->tree->moveTree($ilObjCategory->getRefId(), $parentRefId);
        $this->rbac_admin->adjustMovedObjectPermissions($ilObjCategory->getRefId(), $oldParentRefId);
    }
}
