<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Object\GroupMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class GroupMembershipDTO
 * @package srag\Plugins\Hub2\Object\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupMembershipDTO extends DataTransferObject implements IGroupMembershipDTO
{
    /**
     * @var int
     */
    protected int $ilias_group_ref_id;
    /**
     * @var int
     */
    protected int $user_id;
    /**
     * @var
     */
    protected int $role = self::ROLE_MEMBER;
    /**
     * @var string
     */
    protected string $groupId;
    /**
     * @var int
     */
    protected int $groupIdType;
    /**
     * @var bool
     */
    protected bool $isContact = false;

    /**
     * @inheritdoc
     */
    public function __construct($group_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$group_id, $user_id]));
        $this->setGroupId($group_id);
        $this->setUserId($user_id);
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     * @return GroupMembershipDTO
     */
    public function setGroupId(string $groupId): GroupMembershipDTO
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroupIdType(): int
    {
        return $this->groupIdType;
    }

    /**
     * @param int $groupIdType
     * @return GroupMembershipDTO
     */
    public function setGroupIdType(int $groupIdType): GroupMembershipDTO
    {
        $this->groupIdType = $groupIdType;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return GroupMembershipDTO
     */
    public function setUserId(int $user_id): GroupMembershipDTO
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     * @return GroupMembershipDTO
     */
    public function setRole(mixed $role): GroupMembershipDTO
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return bool
     */
    public function isContact(): bool
    {
        return $this->isContact;
    }

    /**
     * @param bool $isContact
     * @return $this
     */
    public function setIsContact(bool $isContact): GroupMembershipDTO
    {
        $this->isContact = $isContact;

        return $this;
    }
}
