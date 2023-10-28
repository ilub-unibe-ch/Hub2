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

use srag\Plugins\Hub2\Sync\Processor\Category\ICategorySyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Course\ICourseSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\CourseMembership\ICourseMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Group\IGroupSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\GroupMembership\IGroupMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnit\IOrgUnitSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership\IOrgUnitMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\Session\ISessionSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SessionMembership\ISessionMembershipSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\User\IUserSyncProcessor;

/**
 * Interface ISyncProcessorFactory
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISyncProcessorFactory
{
    /**
     * @return IUserSyncProcessor
     */
    public function user(): IUserSyncProcessor;

    /**
     * @return ICourseSyncProcessor
     */
    public function course(): ICourseSyncProcessor;

    /**
     * @return ICategorySyncProcessor
     */
    public function category(): ICategorySyncProcessor;

    /**
     * @return ISessionSyncProcessor
     */
    public function session(): ISessionSyncProcessor;

    /**
     * @return ICourseMembershipSyncProcessor
     */
    public function courseMembership(): ICourseMembershipSyncProcessor;

    /**
     * @return IGroupSyncProcessor
     */
    public function group(): IGroupSyncProcessor;

    /**
     * @return IGroupMembershipSyncProcessor
     */
    public function groupMembership(): IGroupMembershipSyncProcessor;

    /**
     * @return ISessionMembershipSyncProcessor
     */
    public function sessionMembership(): ISessionMembershipSyncProcessor;

    /**
     * @return IOrgUnitSyncProcessor
     */
    public function orgUnit(): IOrgUnitSyncProcessor;

    /**
     * @return IOrgUnitMembershipSyncProcessor
     */
    public function orgUnitMembership(): IOrgUnitMembershipSyncProcessor;
}
