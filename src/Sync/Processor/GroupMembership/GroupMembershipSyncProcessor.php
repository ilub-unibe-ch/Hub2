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

namespace srag\Plugins\Hub2\Sync\Processor\GroupMembership;

use ilObject2;
use ilObjGroup;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\Group\GroupOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\Origin\Properties\Group\GroupProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Object\GroupMembership\IGroupMembershipDTO;
use ilParticipants;

/**
 * Class GroupMembershipSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\GroupMembership
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipSyncProcessor extends ObjectSyncProcessor implements IGroupMembershipSyncProcessor
{
    /**
     * @var GroupProperties
     */
    protected $props;
    /**
     * @var GroupOriginConfig
     */
    protected $config;

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
     * @inheritdoc
     * @param GroupMembershipDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $ilias_group_ref_id = $this->buildParentRefId($dto);

        $group = $this->findILIASGroup($ilias_group_ref_id);
        if (!$group) {
            return;
        }

        $user_id = $dto->getUserId();
        $membership_obj = $group->getMembersObject();
        $membership_obj->add($user_id, $this->mapRole($dto));
        $membership_obj->updateContact($user_id, $dto->isContact());

        $this->current_ilias_object = new FakeIliasMembershipObject($ilias_group_ref_id, $user_id);
    }

    /**
     * @inheritdoc
     * @param GroupMembershipDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

        $ilias_group_ref_id = $this->buildParentRefId($dto);
        $user_id = $dto->getUserId();
        if (!$this->props->updateDTOProperty('role')) {
            $this->current_ilias_object = new FakeIliasMembershipObject($ilias_group_ref_id, $user_id);

            return;
        }

        $group = $this->findILIASGroup($ilias_group_ref_id);
        if (!$group) {
            return;
        }

        $membership_obj = $group->getMembersObject();
        $membership_obj->updateRoleAssignments($user_id, [$this->getILIASRole($dto, $group)]);
        if ($this->props->updateDTOProperty("isContact")) {
            $membership_obj->updateContact($user_id, $dto->isContact());
        }

        $obj->setUserIdIlias($dto->getUserId());
        $obj->setContainerIdIlias($group->getRefId());
        $obj->initId();
    }

    /**
     * @inheritdoc
     */
    protected function handleDelete(string $ilias_id)/*: void*/
    {
        $this->current_ilias_object = $obj = FakeIliasMembershipObject::loadInstanceWithConcatenatedId($ilias_id);

        $group = $this->findILIASGroup($obj->getContainerIdIlias());
        $group->getMembersObject()->delete($obj->getUserIdIlias());
    }

    /**
     * @param int $iliasId
     * @return ilObjGroup|null
     */
    protected function findILIASGroup(int $iliasId): ?ilObjGroup
    {
        if (!ilObject2::_exists($iliasId, true)) {
            return null;
        }

        return new ilObjGroup($iliasId);
    }

    /**
     * @param GroupMembershipDTO $dto
     * @return int
     * @throws HubException
     */
    protected function buildParentRefId(GroupMembershipDTO $dto): int
    {
        if ($dto->getGroupIdType() == IGroupMembershipDTO::PARENT_ID_TYPE_REF_ID) {
            if ($this->tree->isInTree((int)$dto->getGroupId())) {
                return (int) $dto->getGroupId();
            }
            throw new HubException("Could not find the ref-ID of the parent group in the tree: '{$dto->getGroupId()}'");
        }
        if ($dto->getGroupIdType() == IGroupMembershipDTO::PARENT_ID_TYPE_EXTERNAL_EXT_ID) {
            // The stored parent-ID is an external-ID from a group.
            // We must search the parent ref-ID from a group object synced by a linked origin.
            // --> Get an instance of the linked origin and lookup the group by the given external ID.
            $linkedOriginId = $this->config->getLinkedOriginId();
            if (!$linkedOriginId) {
                throw new HubException("Unable to lookup external parent ref-ID because there is no origin linked");
            }
            $originRepository = new OriginRepository();
            $array = array_filter($originRepository->groups(), function ($origin) use ($linkedOriginId) {
                /** @var IOrigin $origin */
                return $origin->getId() == $linkedOriginId;
            });
            $origin = array_pop($array);
            if ($origin === null) {
                $msg = "The linked origin syncing group was not found, please check that the correct origin is linked";
                throw new HubException($msg);
            }
            $objectFactory = new ObjectFactory($origin);
            $group = $objectFactory->group($dto->getGroupId());
            if (!$group->getILIASId()) {
                throw new HubException("The linked group does not (yet) exist in ILIAS");
            }
            if (!$this->tree->isInTree((int)$group->getILIASId())) {
                throw new HubException("Could not find the ref-ID of the parent group in the tree: '{$group->getILIASId()}'");
            }

            return (int) $group->getILIASId();
        }

        return 0;
    }

    /**
     * @param GroupMembershipDTO $object
     * @return int
     */
    protected function mapRole(GroupMembershipDTO $object): int
    {
        switch ($object->getRole()) {
            case IGroupMembershipDTO::ROLE_ADMIN:
                return ilParticipants::IL_GRP_ADMIN;
            case IGroupMembershipDTO::ROLE_MEMBER:
                return ilParticipants::IL_GRP_MEMBER;
            default:
                return ilParticipants::IL_CRS_MEMBER;
        }
    }

    /**
     * @param GroupMembershipDTO $object
     * @param ilObjGroup         $group
     * @return int
     */
    protected function getILIASRole(GroupMembershipDTO $object, ilObjGroup $group): int
    {
        switch ($object->getRole()) {
            case IGroupMembershipDTO::ROLE_ADMIN:
                return $group->getDefaultAdminRole();
            default:
                return $group->getDefaultMemberRole();
        }
    }
}
