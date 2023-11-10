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

namespace srag\Plugins\Hub2\Jobs;

class CronNotifier implements Notifier
{
    public const NOTIFY_MODULO = 500;
    public const PING_MODULO = 500;
    private int $ping_counter = 0;
    private int $notify_counter = 0;
    protected \ilLogger $logger;

    public function __construct()
    {
        ini_set('zend.enable_gc', "1");
        gc_enable();
        global $DIC;
        $this->logger = $DIC->logger()->root();
    }

    public function reset(): void
    {
        $this->ping_counter = 0;
        $this->notify_counter = 0;
    }

    private function pingCronJob(): void
    {
        if (PHP_SAPI === 'cli') {
            global $DIC;
            if (!isset($DIC['cron.repository'])) {
                return;
            }

            $DIC['cron.manager']->ping(RunSync::CRON_JOB_ID);
        }
    }

    public function ping(): void
    {
        if ($this->ping_counter % self::PING_MODULO === 0) {
            $this->pingCronJob();
        }
        $this->ping_counter++;
    }

    public function notify(string $text): void
    {
        $this->pingCronJob();
        $this->logger->write('HUB2: ' . $text);
    }

    public function notifySometimes(string $text): void
    {
        if ($this->notify_counter % self::NOTIFY_MODULO === 0) {
            $this->notify($text . " ($this->notify_counter)");
            if (gc_enabled()) {
                gc_collect_cycles();
            }
        }
        $this->notify_counter++;
    }
}
