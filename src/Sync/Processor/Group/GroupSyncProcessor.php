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

namespace srag\Plugins\Hub2\Sync\Processor\Group;

use ilCalendarCategory;
use ilDate;
use ilObjGroup;
use ilRepUtil;
use ilMD;
use ilMDLanguageItem;
use ilContainer;
use ilContainerSortingSettings;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\TaxonomySyncProcessor;
use srag\Plugins\Hub2\Origin\Properties\Group\IGroupProperties;

/**
 * Class GroupSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupSyncProcessor extends ObjectSyncProcessor implements IGroupSyncProcessor
{
    use TaxonomySyncProcessor;
    use MetadataSyncProcessor;

    /**
     * @var GroupProperties
     */
    protected $props;
    /**
     * @var GroupOriginConfig
     */
    protected $config;
    /**
     * @var IGroupActivities
     */
    protected IGroupActivities $groupActivities;
    /**
     * @var array
     */
    protected static array $properties = [
        "title",
        "description",
        "information",
        "groupType",
        "owner",
        "viewMode",
        "numberOfPreviousSessions",
        "numberOfNextSessions",
        "registrationStart",
        "registrationEnd",
        "password",
        "registrationType",
        "minMembers",
        "maxMembers",
        "waitingListAutoFill",
        "cancellationEnd",
        "start",
        "end",
        "latitude",
        "longitude",
        "locationzoom",
        "registrationAccessCode",
        "enableGroupMap",
    ];
    /**
     * @var array
     */
    protected static array $ildate_fields = [
        "cancellationEnd",
        "start",
        "end",
        "registrationStart",
        "registrationEnd",
    ];

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $transition
     * @param IGroupActivities        $groupActivities
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition,
        IGroupActivities $groupActivities
    ) {
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
        $this->groupActivities = $groupActivities;
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
     * @param GroupDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = new ilObjGroup();
        $ilObjGroup->setImportId($this->getImportId($dto));
        // Find the refId under which this group should be created
        $parentRefId = $this->determineParentRefId($dto);
        // Pass properties from DTO to ilObjUser

        $ilObjGroup->setRegistrationStart(new ilDate(null, IL_CAL_UNIX));
        $ilObjGroup->setRegistrationEnd(new ilDate(null, IL_CAL_UNIX));

        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);

            if ($dto->$getter() !== null) {
                $var = $dto->$getter();
                if (in_array($property, self::$ildate_fields)) {
                    $var = new ilDate($var, IL_CAL_UNIX);
                }
                $ilObjGroup->$setter($var);
            }
        }

        if ($dto->getSessionLimit() !== null) {
            $ilObjGroup->enableSessionLimit($dto->getSessionLimit());
        }

        if ($dto->getRegUnlimited() !== null) {
            $ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
        }

        if ($dto->getRegMembershipLimitation() !== null) {
            $ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());
        }

        if ($dto->getWaitingList() !== null) {
            $ilObjGroup->enableWaitingList($dto->getWaitingList());
        }

        if ($dto->getRegAccessCodeEnabled() !== null) {
            $ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());
        }

        $this->setNewsSetting($dto, $ilObjGroup);
        $ilObjGroup->create();
        $ilObjGroup->createReference();
        $ilObjGroup->putInTree($parentRefId);
        $ilObjGroup->setPermissions($parentRefId);

        $this->handleAppointementsColor($ilObjGroup, $dto);
        $this->setLanguage($dto, $ilObjGroup);
        $this->handleOrdering($dto, $ilObjGroup);

    }

    /**
     * @inheritdoc
     * @param GroupDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = $this->findILIASGroup((int)$ilias_id);
        if ($ilObjGroup === null) {
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
                $var = $dto->$getter();
                if (in_array($property, self::$ildate_fields)) {
                    $var = new ilDate($var, IL_CAL_UNIX);
                }

                $ilObjGroup->$setter($var);
            }
        }

        if ($dto->getSessionLimit() !== null) {
            $ilObjGroup->enableSessionLimit($dto->getSessionLimit());
        }

        if ($this->props->updateDTOProperty("registrationType")
            && $dto->getRegistrationType() !== null) {
            $ilObjGroup->setRegistrationType($dto->getRegistrationType());
        }

        if ($this->props->updateDTOProperty("regUnlimited")
            && $dto->getRegUnlimited() !== null) {
            $ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
        }

        if ($this->props->updateDTOProperty("regMembershipLimitation")
            && $dto->getRegMembershipLimitation() !== null) {
            $ilObjGroup->enableMembershipLimitation($dto->getRegMembershipLimitation());
        }

        if ($this->props->updateDTOProperty("waitingList") && $dto->getWaitingList() !== null) {
            $ilObjGroup->enableWaitingList($dto->getWaitingList());
        }

        if ($this->props->updateDTOProperty("regAccessCodeEnabled")
            && $dto->getRegAccessCodeEnabled() !== null) {
            $ilObjGroup->enableRegistrationAccessCode($dto->getRegAccessCodeEnabled());
        }

        if ($this->props->updateDTOProperty("regUnlimited")
            && $dto->getRegUnlimited() !== null) {
            $ilObjGroup->enableUnlimitedRegistration($dto->getRegUnlimited());
        }

        if ($this->props->updateDTOProperty("appointementsColor")) {
            $this->handleAppointementsColor($ilObjGroup, $dto);
        }

        if ($this->props->updateDTOProperty("languageCode")) {
            $this->setLanguage($dto, $ilObjGroup);
        }

        if ($this->props->updateDTOProperty("orderType")) {
            $this->handleOrdering($dto, $ilObjGroup);
        }

        if ($this->props->get(IGroupProperties::MOVE_GROUP)) {
            $this->moveGroup($ilObjGroup, $dto);
        }
        $this->setNewsSetting($dto, $ilObjGroup);
        $ilObjGroup->update();
    }

    /**
     * @param ilObjGroup $ilObjGroup
     * @param GroupDTO   $dto
     */
    protected function handleAppointementsColor(ilObjGroup $ilObjGroup, GroupDTO $dto)
    {
        if ($dto->getAppointementsColor()) {
            $this->object_data_cache->deleteCachedEntry($ilObjGroup->getId());
            /**
             * @var $cal_cat ilCalendarCategory
             */
            $cal_cat = ilCalendarCategory::_getInstanceByObjId($ilObjGroup->getId());
            $cal_cat->setColor($dto->getAppointementsColor());
            $cal_cat->update();
        }
    }

    /**
     * @inheritdoc
     */
    protected function handleDelete(string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $ilObjGroup = $this->findILIASGroup((int)$ilias_id);
        if ($ilObjGroup === null) {
            return;
        }
        if ($this->props->get(IGroupProperties::DELETE_MODE) == IGroupProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(IGroupProperties::DELETE_MODE)) {
            case IGroupProperties::DELETE_MODE_CLOSED:
                $ilObjGroup->setGroupStatus(2);
                $ilObjGroup->update();
                break;
            case IGroupProperties::DELETE_MODE_DELETE:
                $ilObjGroup->delete();
                break;
            case IGroupProperties::DELETE_MODE_MOVE_TO_TRASH:
                $this->tree->moveToTrash($ilObjGroup->getRefId(), true);
                break;
            case IGroupProperties::DELETE_MODE_DELETE_OR_CLOSE:
                if ($this->groupActivities->hasActivities($ilObjGroup)) {
                    $ilObjGroup->setGroupStatus(2);
                    $ilObjGroup->update();
                } else {
                    $this->tree->moveToTrash($ilObjGroup->getRefId(), true);
                }
                break;
        }
    }

    /**
     * @param GroupDTO   $dto
     * @param ilObjGroup $ilObjGroup
     */
    protected function setLanguage(GroupDTO $dto, ilObjGroup $ilObjGroup)
    {
        $md_general = (new ilMD($ilObjGroup->getId()))->getGeneral();
        $array = $md_general->getLanguageIds();
        $language = $md_general->getLanguage(array_pop($array));
        $language->setLanguage(new ilMDLanguageItem($dto->getLanguageCode()));
        $language->update();
    }

    /**
     * @param GroupDTO   $dto
     * @param ilObjGroup $ilObjGroup
     */
    protected function handleOrdering(GroupDTO $dto, ilObjGroup $ilObjGroup)
    {
        $settings = new ilContainerSortingSettings($ilObjGroup->getId());
        $settings->setSortMode($dto->getOrderType());

        switch ($dto->getOrderType()) {
            case ilContainer::SORT_TITLE:
            case ilContainer::SORT_ACTIVATION:
            case ilContainer::SORT_CREATION:
                $settings->setSortDirection($dto->getOrderDirection());
                break;
            case ilContainer::SORT_MANUAL:
                /**
                 * TODO: set order direction for manual sorting
                 */
                break;
        }

        $settings->update();
    }

    /**
     * @param GroupDTO $group
     * @return int
     * @throws HubException
     */
    protected function determineParentRefId(GroupDTO $group): int
    {
        if ($group->getParentIdType() == GroupDTO::PARENT_ID_TYPE_REF_ID) {
            if ($this->tree->isInTree((int)$group->getParentId())) {
                return (int)$group->getParentId();
            }
            // The ref-ID does not exist in the tree, use the fallback parent ref-ID according to the config
            $parentRefId = $this->config->getParentRefIdIfNoParentIdFound();
            if (!$this->tree->isInTree($parentRefId)) {
                throw new HubException("Could not find the fallback parent ref-ID in tree: '{$parentRefId}'");
            }

            return $parentRefId;
        }
        if ($group->getParentIdType() == GroupDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a category.
            // We must search the parent ref-ID from a category object synced by a linked origin.
            // --> Get an instance of the linked origin and lookup the category by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if (!$linkedOriginId) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $possible_parents = array_merge($originRepository->categories(), $originRepository->courses());
            $array = array_filter($possible_parents, function ($origin) use ($linkedOriginId) {
                /** @var IOrigin $origin */
                return $origin->getId() == $linkedOriginId;
            });
            $origin = array_pop($array);
            if ($origin === null) {
                $msg = "The linked origin syncing categories or courses was not found,
				please check that the correct origin is linked";
                throw new HubException($msg);
            }

            $objectFactory = new ObjectFactory($origin);

            if ($origin instanceof ARCourseOrigin) {
                $parent = $objectFactory->course($group->getParentId());
            } else {
                $parent = $objectFactory->category($group->getParentId());
            }

            if (!$parent->getILIASId()) {
                throw new HubException("The linked category or course does not (yet) exist in ILIAS");
            }
            if (!$this->tree->isInTree((int)$parent->getILIASId())) {
                throw new HubException("Could not find the ref-ID of the parent category or course in the tree: '{$parent->getILIASId()}'");
            }

            return (int)$parent->getILIASId();
        }

        return 0;
    }

    /**
     * @param int $iliasId
     * @return ilObjGroup|null
     */
    protected function findILIASGroup(int $iliasId): ?ilObjGroup
    {
        if (!ilObjGroup::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjGroup($iliasId);
    }

    /**
     * Move the group to a new parent.
     * Note: May also create the dependence categories
     * @param ilObjGroup $ilObjGroup
     * @param GroupDTO   $group
     */
    protected function moveGroup(ilObjGroup $ilObjGroup, GroupDTO $group)
    {
        $parentRefId = $this->determineParentRefId($group);
        if ($this->tree->isDeleted($ilObjGroup->getRefId())) {
            $ilRepUtil = new ilRepUtil();
            $ilRepUtil->restoreObjects($parentRefId, [$ilObjGroup->getRefId()]);
        }
        $oldParentRefId = $this->tree->getParentId($ilObjGroup->getRefId());
        if ($oldParentRefId == $parentRefId) {
            return;
        }
        $this->tree->moveTree($ilObjGroup->getRefId(), $parentRefId);
        $this->rbac_admin->adjustMovedObjectPermissions($ilObjGroup->getRefId(), $oldParentRefId);
    }

    /**
     * @param GroupDTO   $dto
     * @param ilObjGroup $ilObjGroup
     */
    protected function setNewsSetting(GroupDTO $dto, ilObjGroup $ilObjGroup)
    {
        $ilObjGroup->setUseNews($dto->getNewsSetting());
        $ilObjGroup->setNewsBlockActivated($dto->getNewsSetting());
    }
}
