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

namespace srag\Plugins\Hub2\Sync\Summary;

use hub2LogsGUI;
use ilHub2Plugin;
use ilMimeMail;

use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Sync\IOriginSync;


/**
 * Class OriginSyncSummaryCron
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Sync\Summary
 */
abstract class OriginSyncSummaryBase implements IOriginSyncSummary
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOriginSync[]
     */
    protected array $syncs = [];

    /**
     *OriginSyncSummaryCron constructor
     */
    public function __construct()
    {

    }

    /**
     * @inheritdoc
     */
    public function addOriginSync(IOriginSync $originSync)
    {
        $this->syncs[] = $originSync;
    }

    /**
     * @inheritdoc
     */
    public function getOutputAsString(): string
    {
        $return = "";
        foreach ($this->syncs as $sync) {
            $return .= $this->renderOneSync($sync) . "\n\n";
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function sendEmail()
    {
        $mail = new ilMimeMail();

        $mail->From(self::dic()->mailMimeSenderFactory()->system());

        foreach ($this->syncs as $originSync) {
            $summary_email = $originSync->getOrigin()->config()->getNotificationsSummary();
            $error_email = $originSync->getOrigin()->config()->getNotificationsErrors();

            $title = $originSync->getOrigin()->getTitle();

            if ($summary_email) {
                $mail->To($summary_email);

                $mail->Subject(ilHub2Plugin::getInstance()->txt("summary_notification", "", [$title]));
                $mail->Body($this->renderOneSync($originSync));

                $mail->Send();
            }

            if ($error_email) {
                if (count(self::logs()->getKeptLogs($originSync->getOrigin())) > 0) {
                    $mail->To($error_email);

                    $mail->Subject(ilHub2Plugin::getInstance()->txt(
                        "summary_logs_in",
                        hub2LogsGUI::LANG_MODULE_LOGS,
                        [$title]
                    ));

                    $mail->Body($this->renderOneSync($originSync, true));

                    $mail->Send();
                }
            }
        }
    }

    /**
     * @param IOriginSync $originSync
     * @param bool        $only_logs
     * @param bool        $output_message
     * @return string
     */
    protected function renderOneSync(
        IOriginSync $originSync,
        bool $only_logs = false,
        bool $output_message = null
    ): string {
        $msg = "";
        if (!$only_logs) {
            // Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
            $msg .= ilHub2Plugin::getInstance()->txt("summary_for", "", [$originSync->getOrigin()->getTitle()]) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_delivered_data_sets",
                "",
                [$originSync->getCountDelivered()]
            ) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_failed",
                "",
                [$originSync->getCountProcessedByStatus(IObject::STATUS_FAILED)]
            ) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_created",
                "",
                [$originSync->getCountProcessedByStatus(IObject::STATUS_CREATED)]
            ) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_updated",
                "",
                [$originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED)]
            ) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_outdated",
                "",
                [$originSync->getCountProcessedByStatus(IObject::STATUS_OUTDATED)]
            ) . "\n";
            $msg .= ilHub2Plugin::getInstance()->txt(
                "summary_ignored",
                "",
                [$originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED)]
            );
        }

        if (count(self::logs()->getKeptLogs($originSync->getOrigin())) > 0) {
            $msg .= "\n" . ilHub2Plugin::getInstance()->txt("summary", hub2LogsGUI::LANG_MODULE_LOGS) . "\n";

            $msg .= implode("\n", array_map(function (int $level) use ($output_message, $originSync): string {
                $logs = self::logs()->getKeptLogs($originSync->getOrigin(), $level);

                return ilHub2Plugin::getInstance()->txt(
                    "level_" . $level,
                    hub2LogsGUI::LANG_MODULE_LOGS
                ) . ": " . count($logs) . ($output_message ? " - "
                        . current($logs)->getMessage() : "");
            }, array_filter(Log::$levels, function (int $level) use ($originSync): bool {
                return (count(self::logs()->getKeptLogs($originSync->getOrigin(), $level)) > 0);
            })));
        }

        return $msg;
    }
}
