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

use ilHub2Plugin;
use ilMimeMail;
use srag\Plugins\Hub2\Log\Log;
use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Sync\IOriginSync;
use srag\Plugins\Hub2\Log\Repository as LogRepository;
use ilMailMimeSenderFactory;
use srag\Plugins\Hub2\Log\ILog;

/**
 * Class OriginSyncSummaryCron
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Sync\Summary
 */
abstract class OriginSyncSummaryBase implements IOriginSyncSummary
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    protected \srag\Plugins\Hub2\Log\IRepository $log_repo;
    protected ilHub2Plugin $plugin;
    /**
     * @var IOriginSync[]
     */
    protected array $syncs = [];
    private ilMailMimeSenderFactory $sender_factory;

    /**
     *OriginSyncSummaryCron constructor
     */
    public function __construct()
    {
        global $DIC;
        $this->sender_factory = $DIC['mail.mime.sender.factory'];
        $this->plugin = ilHub2Plugin::getInstance();
        $this->log_repo = LogRepository::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function addOriginSync(IOriginSync $originSync): void
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
    public function sendEmail(): void
    {
        $mail = new ilMimeMail();

        $mail->From($this->sender_factory->system());

        foreach ($this->syncs as $originSync) {
            $summary_email = $originSync->getOrigin()->config()->getNotificationsSummary();
            $error_email = $originSync->getOrigin()->config()->getNotificationsErrors();

            $title = $originSync->getOrigin()->getTitle();

            if ($summary_email !== []) {
                $mail->To($summary_email);

                $mail->Subject($this->plugin->txt("summary_notification"));
                $mail->Body($this->renderOneSync($originSync));

                $mail->Send();
            }

            if ($error_email !== [] && (count(
                $this->log_repo->getKeptLogs($originSync->getOrigin(), ILog::LEVEL_EXCEPTION)
            )
                    + count($this->log_repo->getKeptLogs($originSync->getOrigin(), ILog::LEVEL_CRITICAL)))
                > 0) {
                $mail->To($error_email);
                $mail->Subject(
                    sprintf($this->plugin->txt("logs_summary_logs_in"), $title)
                );
                $mail->Body($this->renderOneSync($originSync, true, true));
                $mail->Send();
            }
        }
    }

    protected function renderOneSync(
        IOriginSync $originSync,
        bool $only_logs = false,
        bool $output_message = null
    ): string {
        $msg = "";
        if (!$only_logs) {
            // Print out some useful statistics: --> Should maybe be a OriginSyncSummary object
            $msg .= sprintf($this->plugin->txt("summary_for"), $originSync->getOrigin()->getTitle()) . "\n";
            $msg .= sprintf($this->plugin->txt("summary_delivered_data_sets"), $originSync->getCountDelivered()) . "\n";
            $msg .= sprintf(
                $this->plugin->txt("summary_failed"),
                $originSync->getCountProcessedByStatus(IObject::STATUS_FAILED)
            ) . "\n";
            $msg .= sprintf(
                $this->plugin->txt("summary_created"),
                $originSync->getCountProcessedByStatus(IObject::STATUS_CREATED)
            ) . "\n";
            $msg .= sprintf(
                $this->plugin->txt("summary_updated"),
                $originSync->getCountProcessedByStatus(IObject::STATUS_UPDATED)
            ) . "\n";
            $msg .= sprintf(
                $this->plugin->txt("summary_outdated"),
                $originSync->getCountProcessedByStatus(IObject::STATUS_OUTDATED)
            ) . "\n";
            $msg .= sprintf(
                $this->plugin->txt("summary_ignored"),
                $originSync->getCountProcessedByStatus(IObject::STATUS_IGNORED)
            );
        }

        if ($this->log_repo->getKeptLogs($originSync->getOrigin()) !== []) {
            $msg .= "\n" . $this->plugin->txt("logs_summary") . "\n";

            $msg .= implode(
                "\n",
                array_map(
                    function (int $level) use ($output_message, $originSync): string {
                        $logs = $this->log_repo->getKeptLogs($originSync->getOrigin(), $level);

                        return $this->plugin->txt("logs_level_" . $level)
                            . ": " . count($logs) . ($output_message ? " - "
                                . current($logs)->getMessage() : "");
                    },
                    array_filter(
                        Log::$levels,
                        function (int $level) use ($originSync): bool {
                            return ((is_countable(
                                $this->log_repo->getKeptLogs($originSync->getOrigin(), $level)
                            ) ? count($this->log_repo->getKeptLogs($originSync->getOrigin(), $level)) : 0) > 0);
                        }
                    )
                )
            );
        }

        return $msg;
    }
}
