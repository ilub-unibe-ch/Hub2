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

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Config\ArConfigOld;
use srag\Plugins\Hub2\Jobs\Log\DeleteOldLogsJob;
use srag\Plugins\Hub2\Jobs\RunSync;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\Category\ARCategory;
use srag\Plugins\Hub2\Object\Course\ARCourse;
use srag\Plugins\Hub2\Object\CourseMembership\ARCourseMembership;
use srag\Plugins\Hub2\Object\Group\ARGroup;
use srag\Plugins\Hub2\Object\GroupMembership\ARGroupMembership;
use srag\Plugins\Hub2\Object\OrgUnit\AROrgUnit;
use srag\Plugins\Hub2\Object\OrgUnitMembership\AROrgUnitMembership;
use srag\Plugins\Hub2\Object\Session\ARSession;
use srag\Plugins\Hub2\Object\SessionMembership\ARSessionMembership;
use srag\Plugins\Hub2\Object\User\ARUser;
use srag\RemovePluginDataConfirm\Hub2\PluginUninstallTrait;
use srag\Plugins\Hub2\Origin\AROrigin;

/**
 * Class ilHub2Plugin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2Plugin extends ilCronHookPlugin
{
    public const PLUGIN_ID = 'hub2';
    public const PLUGIN_NAME = 'Hub2';
    public const PLUGIN_CLASS_NAME = self::class;
    public const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = hub2RemoveDataConfirm::class;
    /**
     * @var self
     */
    protected static ilHub2Plugin $instance;
    protected ilDBInterface $database;

    public function __construct()
    {
        global $DIC;
        $this->database = $DIC->database();
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getPluginName(): string
    {
        return self::PLUGIN_NAME;
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return ilCronJob[]
     */
    public function getCronJobInstances(): array
    {
        return [new RunSync(), new DeleteOldLogsJob()];
    }

    public function getCronJobInstance(string $a_job_id): ilCronJob
    {
        switch ($a_job_id) {
            case RunSync::CRON_JOB_ID:
                return new RunSync();

            case DeleteOldLogsJob::CRON_JOB_ID:
                return new DeleteOldLogsJob();
        }
        return new RunSync();
    }

    /**
     * @inheritdoc
     */
    protected function deleteData()/*: void*/
    {
        $this->database->dropTable(AROrigin::TABLE_NAME, false);
        $this->database->dropTable(ARUser::TABLE_NAME, false);
        $this->database->dropTable(ARCourse::TABLE_NAME, false);
        $this->database->dropTable(ARCourseMembership::TABLE_NAME, false);
        $this->database->dropTable(ARCategory::TABLE_NAME, false);
        $this->database->dropTable(ARSession::TABLE_NAME, false);
        $this->database->dropTable(ARGroup::TABLE_NAME, false);
        $this->database->dropTable(ARGroupMembership::TABLE_NAME, false);
        $this->database->dropTable(ARSessionMembership::TABLE_NAME, false);
        $this->database->dropTable(ArConfig::TABLE_NAME, false);
        $this->database->dropTable(ArConfigOld::TABLE_NAME, false);
        $this->database->dropTable(AROrgUnit::TABLE_NAME, false);
        $this->database->dropTable(AROrgUnitMembership::TABLE_NAME, false);
        $this->database->dropTable(Log::TABLE_NAME, false);

        ilUtil::delDir(ILIAS_DATA_DIR . "/hub/");
    }

    /**
     * @return bool
     */
    protected function shouldUseOneUpdateStepOnly(): bool
    {
        return false;
    }
}
