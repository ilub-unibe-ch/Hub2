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

namespace srag\Plugins\Hub2\Origin;

use srag\Plugins\Hub2\Origin\Category\ICategoryOrigin;
use srag\Plugins\Hub2\Origin\Course\ICourseOrigin;
use srag\Plugins\Hub2\Origin\CourseMembership\ICourseMembershipOrigin;
use srag\Plugins\Hub2\Origin\Group\IGroupOrigin;
use srag\Plugins\Hub2\Origin\GroupMembership\IGroupMembershipOrigin;
use srag\Plugins\Hub2\Origin\OrgUnit\IOrgUnitOrigin;
use srag\Plugins\Hub2\Origin\OrgUnitMembership\IOrgUnitMembershipOrigin;
use srag\Plugins\Hub2\Origin\Session\ISessionOrigin;
use srag\Plugins\Hub2\Origin\SessionMembership\ISessionMembershipOrigin;
use srag\Plugins\Hub2\Origin\User\IUserOrigin;

/**
 * Interface IOriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginRepository
{
    /**
     * Returns all available origins in the correct order of the syncing process:
     * Users > Categories > Courses > CourseMemberShips > Groups > GroupMemberships > Sessions
     * @return IOrigin[]
     */
    public function all(): array;

    /**
     * Same as all() without inactive origins
     * @return IOrigin[]
     */
    public function allActive(): array;

    /**
     * Returns the origins of object type user
     * @return IUserOrigin[]
     */
    public function users(): array;

    /**
     * @return ICourseOrigin[]
     */
    public function courses(): array;

    /**
     * @return ICategoryOrigin[]
     */
    public function categories(): array;

    /**
     * @return ICourseMembershipOrigin[]
     */
    public function courseMemberships(): array;

    /**
     * @return IGroupOrigin[]
     */
    public function groups(): array;

    /**
     * @return IGroupMembershipOrigin[]
     */
    public function groupMemberships(): array;

    /**
     * @return ISessionOrigin[]
     */
    public function sessions(): array;

    /**
     * @return ISessionMembershipOrigin[]
     */
    public function sessionsMemberships(): array;

    /**
     * @return IOrgUnitOrigin[]
     */
    public function orgUnits(): array;

    /**
     * @return IOrgUnitMembershipOrigin[]
     */
    public function orgUnitMemberships(): array;
}
