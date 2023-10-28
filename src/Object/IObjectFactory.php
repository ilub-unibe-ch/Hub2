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

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Category\ICategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\Course\ICourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\CourseMembership\ICourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\Group\IGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\GroupMembership\IGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\Session\ISession;
use srag\Plugins\Hub2\Object\SessionMembership\ISessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Object\User\IUser;

/**
 * Interface IObjectFactory
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObjectFactory
{
    /**
     * Get the primary ID of an object. In the ActiveRecord implementation, the primary key is a
     * concatenation of the origins ID with the external-ID, see IObject::create()
     * @param string $ext_id
     * @return string
     */
    public function getId(string $ext_id): string;

    /**
     * @param string $ext_id
     * @return ActiveRecord|ARCategory|ICategory|ARCourse|ICourse|ARCourseMembership|ICourseMembership|ARGroup|IGroup|ARGroupMembership|IGroupMembership|ARSession|ISession|ARUser|IUser|IOrgUnit|AROrgUnit|IOrgUnitMembership|AROrgUnitMembership
     */
    public function undefined(string $ext_id);

    /**
     * @param string $ext_id
     * @return IUser
     */
    public function user(string $ext_id): IUser;

    /**
     * @param string $ext_id
     * @return ICourse
     */
    public function course(string $ext_id): ICourse;

    /**
     * @param string $ext_id
     * @return ICategory
     */
    public function category(string $ext_id): ICategory;

    /**
     * @param string $ext_id
     * @return IGroup
     */
    public function group(string $ext_id): IGroup;

    /**
     * @param string $ext_id
     * @return ISession
     */
    public function session(string $ext_id): ISession;

    /**
     * @param string $ext_id
     * @return ICourseMembership
     */
    public function courseMembership(string $ext_id): ICourseMembership;

    /**
     * @param string $ext_id
     * @return IGroupMembership
     */
    public function groupMembership(string $ext_id): IGroupMembership;

    /**
     * @param string $ext_id
     * @return ISessionMembership
     */
    public function sessionMembership(string $ext_id): ISessionMembership;

    /**
     * @param string $ext_id
     * @return IOrgUnit
     */
    public function orgUnit(string $ext_id): IOrgUnit;

    /**
     * @param string $ext_id
     * @return IOrgUnitMembership
     */
    public function orgUnitMembership(string $ext_id): IOrgUnitMembership;

    /**
     * @return IUser[]
     */
    public function users(): array;

    /**
     * @return ICourse[]
     */
    public function courses(): array;

    /**
     * @return ICategory[]
     */
    public function categories(): array;

    /**
     * @return IGroup[]
     */
    public function groups(): array;

    /**
     * @return ISession[]
     */
    public function sessions(): array;

    /**
     * @return ICourseMembership[]
     */
    public function courseMemberships(): array;

    /**
     * @return IGroupMembership[]
     */
    public function groupMemberships(): array;

    /**
     * @return ISessionMembership[]
     */
    public function sessionMemberships(): array;

    /**
     * @return IOrgUnit[]
     */
    public function orgUnits(): array;

    /**
     * @return IOrgUnitMembership[]
     */
    public function orgUnitMemberships(): array;
}
