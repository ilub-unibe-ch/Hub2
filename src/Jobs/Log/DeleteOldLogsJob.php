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

namespace srag\Plugins\Hub2\Jobs\Log;

use hub2LogsGUI;
use ilCronJob;
use ilCronJobResult;
use ilDateTime;
use ilHub2Plugin;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Jobs\Result\ResultFactory;
use srag\Plugins\Hub2\Log\Log;


/**
 * Class RunSync
 * @package srag\Plugins\Hub2\Jobs\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DeleteOldLogsJob extends ilCronJob
{
    public const CRON_JOB_ID = ilHub2Plugin::PLUGIN_ID . "_delete_old_logs";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * Get id
     * @return string
     */
    public function getId(): string
    {
        return self::CRON_JOB_ID;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return ilHub2Plugin::PLUGIN_NAME . ": " . ilHub2Plugin::getInstance()->txt("cron", hub2LogsGUI::LANG_MODULE_LOGS);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return ilHub2Plugin::getInstance()->txt("cron_description", hub2LogsGUI::LANG_MODULE_LOGS);
    }

    /**
     * Is to be activated on "installation"
     * @return boolean
     */
    public function hasAutoActivation(): bool
    {
        return true;
    }

    /**
     * Can the schedule be configured?
     * @return boolean
     */
    public function hasFlexibleSchedule(): bool
    {
        return true;
    }

    /**
     * Get schedule type
     * @return int
     */
    public function getDefaultScheduleType(): int
    {
        return self::SCHEDULE_TYPE_DAILY;
    }

    /**
     * Get schedule value
     * @return array|int|null
     */
    public function getDefaultScheduleValue()
    {
        return null;
    }

    /**
     * Run job
     * @return ilCronJobResult
     */
    public function run(): ilCronJobResult
    {
        $keep_old_logs_time = ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME);
        $time = time();
        $keep_old_logs_time_timestamp = ($time - ($keep_old_logs_time * 24 * 60 * 60));
        $keep_old_logs_time_date = new ilDateTime($keep_old_logs_time_timestamp, IL_CAL_UNIX);

        $keep_log_ids = [];
        $result = self::dic()->database()->query("SELECT MAX(log_id) AS log_id FROM " . Log::TABLE_NAME . " GROUP BY origin_id,object_ext_id");
        while (($row = $result->fetchAssoc()) !== false) {
            $keep_log_ids[] = intval($row["log_id"]);
        }

        $count = self::dic()->database()->manipulateF(
            "DELETE FROM " . Log::TABLE_NAME . " WHERE date<%s AND " . self::dic()->database()
                                                                                                                     ->in(
                                                                                                                         "log_id",
                                                                                                                         $keep_log_ids,
                                                                                                                         true,
                                                                                                                         "integer"
                                                                                                                     ),
            ["text"],
            [$keep_old_logs_time_date->get(IL_CAL_DATETIME)]
        );

        return ResultFactory::ok(ilHub2Plugin::getInstance()->txt("deleted_status", hub2LogsGUI::LANG_MODULE_LOGS, [$count]));
    }
}
