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

namespace srag\Plugins\Hub2\Object\DTO;

use ilHub2Plugin;

use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnit\OrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\OrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;


/**
 * Class DataTransferObjectFactory
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DataTransferObjectFactory implements IDataTransferObjectFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function user(string $ext_id): UserDTO
    {
        return new UserDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function course(string $ext_id): CourseDTO
    {
        return new CourseDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function category(string $ext_id): CategoryDTO
    {
        return new CategoryDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function group(string $ext_id): GroupDTO
    {
        return new GroupDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function session(string $ext_id): SessionDTO
    {
        return new SessionDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function courseMembership(int $course_id, int $user_id): CourseMembershipDTO
    {
        return new CourseMembershipDTO($course_id, $user_id);
    }

    /**
     * @inheritdoc
     */
    public function groupMembership(int $group_id, int $user_id): GroupMembershipDTO
    {
        return new GroupMembershipDTO($group_id, $user_id);
    }

    /**
     * @inheritdoc
     */
    public function sessionMembership(int $session_id, int $user_id): SessionMembershipDTO
    {
        return new SessionMembershipDTO($session_id, $user_id);
    }

    /**
     * @inheritdoc
     */
    public function orgUnit(string $ext_id): IOrgUnitDTO
    {
        return new OrgUnitDTO($ext_id);
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMembership(int|string $org_unit_id, int $user_id, int $position): IOrgUnitMembershipDTO
    {
        return new OrgUnitMembershipDTO($org_unit_id, $user_id, $position);
    }
}
