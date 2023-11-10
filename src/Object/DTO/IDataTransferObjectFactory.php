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

use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\CourseMembership\CourseMembershipDTO;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\GroupMembership\GroupMembershipDTO;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnitDTO;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembershipDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\SessionMembership\SessionMembershipDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Interface IDataTransferObjectFactory
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDataTransferObjectFactory
{
    /**
     * @param string $ext_id
     * @return UserDTO
     */
    public function user(string $ext_id): UserDTO;

    /**
     * @param string $ext_id
     * @return CourseDTO
     */
    public function course(string $ext_id): CourseDTO;

    /**
     * @param string $ext_id
     * @return CategoryDTO
     */
    public function category(string $ext_id): CategoryDTO;

    /**
     * @param string $ext_id
     * @return GroupDTO
     */
    public function group(string $ext_id): GroupDTO;

    /**
     * @param string $ext_id
     * @return SessionDTO
     */
    public function session(string $ext_id): SessionDTO;

    /**
     * @param int $course_id
     * @param int $user_id
     * @return CourseMembershipDTO
     */
    public function courseMembership(string $course_id, int $user_id): CourseMembershipDTO;

    /**
     * @param int $group_id
     * @param int $user_id
     * @return GroupMembershipDTO
     */
    public function groupMembership(string $group_id, int $user_id): GroupMembershipDTO;

    /**
     * @param int $session_id
     * @param int $user_id
     * @return SessionMembershipDTO
     */
    public function sessionMembership(string $session_id, int $user_id): SessionMembershipDTO;

    /**
     * @param string $ext_id
     * @return IOrgUnitDTO
     */
    public function orgUnit(string $ext_id): IOrgUnitDTO;

    /**
     * @param int|string $org_unit_id
     * @param int        $user_id
     * @param int        $position
     * @return IOrgUnitMembershipDTO
     */
    public function orgUnitMembership(string $org_unit_id, int $user_id, int $position): IOrgUnitMembershipDTO;
}
