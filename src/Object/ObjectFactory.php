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

use ilHub2Plugin;
use LogicException;

use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnit\IOrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\OrgUnitMembership\IOrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\Plugins\Hub2\Origin\IOrigin;


/**
 * Class ObjectFactory
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ObjectFactory implements IObjectFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOrigin
     */
    protected IOrigin $origin;

    /**
     * @param IOrigin $origin
     */
    public function __construct(IOrigin $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @inheritdoc
     */
    public function undefined(string $ext_id)
    {
        switch ($this->origin->getObjectType()) {
            case IOrigin::OBJECT_TYPE_USER:
                return $this->user($ext_id);
            case IOrigin::OBJECT_TYPE_COURSE:
                return $this->course($ext_id);
            case IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP:
                return $this->courseMembership($ext_id);
            case IOrigin::OBJECT_TYPE_CATEGORY:
                return $this->category($ext_id);
            case IOrigin::OBJECT_TYPE_GROUP:
                return $this->group($ext_id);
            case IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP:
                return $this->groupMembership($ext_id);
            case IOrigin::OBJECT_TYPE_SESSION:
                return $this->session($ext_id);
            case IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP:
                return $this->sessionMembership($ext_id);
            case IOrigin::OBJECT_TYPE_ORGNUNIT:
                return $this->orgUnit($ext_id);
            case IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP:
                return $this->orgUnitMembership($ext_id);
            default:
                throw new LogicException('no object-type for this origin found');
        }
    }

    /**
     * @inheritdoc
     */
    public function user(string $ext_id)
    {
        $user = ARUser::find($this->getId($ext_id));
        if ($user === null) {
            $user = new ARUser();
            $user->setOriginId($this->origin->getId());
            $user->setExtId($ext_id);
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function course(string $ext_id)
    {
        $course = ARCourse::find($this->getId($ext_id));
        if ($course === null) {
            $course = new ARCourse();
            $course->setOriginId($this->origin->getId());
            $course->setExtId($ext_id);
        }

        return $course;
    }

    /**
     * @inheritdoc
     */
    public function category(string $ext_id)
    {
        $category = ARCategory::find($this->getId($ext_id));
        if ($category === null) {
            $category = new ARCategory();
            $category->setOriginId($this->origin->getId());
            $category->setExtId($ext_id);
        }

        return $category;
    }

    /**
     * @inheritdoc
     */
    public function group(string $ext_id)
    {
        $group = ARGroup::find($this->getId($ext_id));
        if ($group === null) {
            $group = new ARGroup();
            $group->setOriginId($this->origin->getId());
            $group->setExtId($ext_id);
        }

        return $group;
    }

    /**
     * @inheritdoc
     */
    public function session(string $ext_id)
    {
        $session = ARSession::find($this->getId($ext_id));
        if ($session === null) {
            $session = new ARSession();
            $session->setOriginId($this->origin->getId());
            $session->setExtId($ext_id);
        }

        return $session;
    }

    /**
     * @inheritdoc
     */
    public function courseMembership(string $ext_id)
    {
        $course_membership = ARCourseMembership::find($this->getId($ext_id));
        if ($course_membership === null) {
            $course_membership = new ARCourseMembership();
            $course_membership->setOriginId($this->origin->getId());
            $course_membership->setExtId($ext_id);
        }

        return $course_membership;
    }

    /**
     * @inheritdoc
     */
    public function groupMembership(string $ext_id)
    {
        $group_membership = ARGroupMembership::find($this->getId($ext_id));
        if ($group_membership === null) {
            $group_membership = new ARGroupMembership();
            $group_membership->setOriginId($this->origin->getId());
            $group_membership->setExtId($ext_id);
        }

        return $group_membership;
    }

    /**
     * @inheritdoc
     */
    public function sessionMembership(string $ext_id)
    {
        $session_membership = ARSessionMembership::find($this->getId($ext_id));
        if ($session_membership === null) {
            $session_membership = new ARSessionMembership();
            $session_membership->setOriginId($this->origin->getId());
            $session_membership->setExtId($ext_id);
        }

        return $session_membership;
    }

    /**
     * @inheritdoc
     */
    public function orgUnit(string $ext_id): IOrgUnit
    {
        $org_unit = AROrgUnit::find($this->getId($ext_id));
        if ($org_unit === null) {
            $org_unit = new AROrgUnit();
            $org_unit->setOriginId($this->origin->getId());
            $org_unit->setExtId($ext_id);
        }

        return $org_unit;
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMembership(string $ext_id): IOrgUnitMembership
    {
        $org_unit_membership = AROrgUnitMembership::find($this->getId($ext_id));
        if ($org_unit_membership === null) {
            $org_unit_membership = new AROrgUnitMembership();
            $org_unit_membership->setOriginId($this->origin->getId());
            $org_unit_membership->setExtId($ext_id);
        }

        return $org_unit_membership;
    }

    /**
     * @inheritdoc
     */
    public function getId(string $ext_id): string
    {
        return $this->origin->getId() . $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function users(): array
    {
        return ARUser::get();
    }

    /**
     * @inheritdoc
     */
    public function courses(): array
    {
        return ARCourse::get();
    }

    /**
     * @inheritdoc
     */
    public function categories(): array
    {
        return ARCategory::get();
    }

    /**
     * @inheritdoc
     */
    public function groups(): array
    {
        return ARGroup::get();
    }

    /**
     * @inheritdoc
     */
    public function sessions(): array
    {
        return ARSession::get();
    }

    /**
     * @inheritdoc
     */
    public function courseMemberships(): array
    {
        return ARCourseMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function groupMemberships(): array
    {
        return ARGroupMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function sessionMemberships(): array
    {
        return ARSessionMembership::get();
    }

    /**
     * @inheritdoc
     */
    public function orgUnits(): array
    {
        return AROrgUnit::get();
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMemberships(): array
    {
        return AROrgUnitMembership::get();
    }
}
