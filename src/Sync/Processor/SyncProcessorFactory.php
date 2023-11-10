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

namespace srag\Plugins\Hub2\Sync\Processor;

use ilHub2Plugin;

use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\Category\CategorySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Course\CourseActivities;
use srag\Plugins\Hub2\Sync\Processor\Course\CourseSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CourseMembership\CourseMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Group\GroupActivities;
use srag\Plugins\Hub2\Sync\Processor\Group\GroupSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\GroupMembership\GroupMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\OrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\OrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Session\SessionSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SessionMembership\SessionMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\User\UserSyncProcessor;

/**
 * Class SyncProcessorFactory
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SyncProcessorFactory implements ISyncProcessorFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected \ilDBInterface $database;

    /**
     * @var IOrigin
     */
    protected IOrigin $origin;
    /**
     * @var IObjectStatusTransition
     */
    protected IObjectStatusTransition $statusTransition;
    /**
     * @var IOriginImplementation
     */
    protected IOriginImplementation $implementation;

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $statusTransition
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $statusTransition
    ) {
        global $DIC;

        $this->database = $DIC->database();
        $this->origin = $origin;
        $this->statusTransition = $statusTransition;
        $this->implementation = $implementation;
    }

    /**
     * @inheritdoc
     */
    public function user(): UserSyncProcessor
    {
        return new UserSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function course(): CourseSyncProcessor
    {
        return new CourseSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition,
            new CourseActivities($this->database)
        );
    }

    /**
     * @inheritdoc
     */
    public function category(): CategorySyncProcessor
    {
        return new CategorySyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function session(): SessionSyncProcessor
    {
        return new SessionSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function courseMembership(): CourseMembershipSyncProcessor
    {
        return new CourseMembershipSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function group(): GroupSyncProcessor
    {
        return new GroupSyncProcessor(
            $this->origin,
            $this->implementation,
            $this->statusTransition,
            new GroupActivities($this->database)
        );
    }

    /**
     * @inheritdoc
     */
    public function groupMembership(): GroupMembershipSyncProcessor
    {
        return new GroupMembershipSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function sessionMembership(): SessionMembershipSyncProcessor
    {
        return new SessionMembershipSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function orgUnit(): IOrgUnitSyncProcessor
    {
        return new OrgUnitSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }

    /**
     * @inheritdoc
     */
    public function orgUnitMembership(): IOrgUnitMembershipSyncProcessor
    {
        return new OrgUnitMembershipSyncProcessor($this->origin, $this->implementation, $this->statusTransition);
    }
}
