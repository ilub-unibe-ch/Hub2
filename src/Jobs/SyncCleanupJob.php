<?php

namespace srag\Plugins\Hub2\Jobs;

use ilCronJobResult;
use ILIAS\Cron\Schedule\CronJobScheduleType;
use srag\Plugins\Hub2\Jobs\Sync\Cleanup\CleanupSettings;
use srag\Plugins\Hub2\Jobs\Sync\Cleanup\SyncCleanupService;
use srag\Plugins\Hub2\Jobs\Sync\Persistence\DbAdHocDataRepository;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\Repository as LogRepo;

class SyncCleanupJob extends \ilCronJob
{
    public const CRON_JOB_ID = "Sync-Cleanup-Job";
    private const CRON_JOB_TITLE = "Cleanup_Job_Title";
    private const CRON_JOB_DESCRIPTION = "Cleanup_Job_Description";

    private \ilHub2Plugin $plugin;

    public function __construct(){
        $this->plugin = \ilHub2Plugin::getInstance();
    }


    public function getId(): string
    {
        return self::CRON_JOB_ID;

    }

    public function getTitle(): string
    {
        return $this->plugin->txt(self::CRON_JOB_TITLE);
    }

    public function getDescription(): string
    {
        return $this->plugin->txt(self::CRON_JOB_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function hasAutoActivation(): bool
    {
        return true;
    }

    public function hasFlexibleSchedule(): bool
    {
        return true;
    }

    public function getDefaultScheduleType(): CronJobScheduleType
    {
        return CronJobScheduleType::SCHEDULE_TYPE_DAILY;
    }

    public function getDefaultScheduleValue(): ?int
    {
        return 1;
    }

    /**
     * @throws \Exception
     */
    public function run(): ilCronJobResult
    {
        global $DIC;

        $result = new ilCronJobResult();

        $runId = bin2hex(random_bytes(4));
        $startedAt = microtime(true);

        $cleanupSettings = new CleanupSettings();

        $retentionDays = $cleanupSettings->getRetentionDays();
        $batchSize     = $cleanupSettings->getBatchSize();
        $maxBatches    = $cleanupSettings->getMaxBatches();

        $cutoff = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
            ->modify("-" . $retentionDays . " days")
            ->format('Y-m-d H:i:s');

        try {
            $repo = new DbAdHocDataRepository($DIC->database());
            $service = new SyncCleanupService($repo);

            $summary = $service->cleanupProcessedBefore(
                $cutoff,
                $batchSize,
                $maxBatches,
                null
            );

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            $msg = sprintf(
                "Archive+Cleanup OK: %d records in %d batch(es) (cutoff %s UTC)%s",
                $summary->deleted,
                $summary->batches,
                $cutoff,
                $summary->stoppedByLimit ? " [limit reached]" : ""
            );

            $result->setStatus(ilCronJobResult::STATUS_OK);
            $result->setMessage($this->truncate($msg, 380));

            $level = $summary->stoppedByLimit ? ILog::LEVEL_WARNING : ILog::LEVEL_INFO;
            $logMessage = $summary->stoppedByLimit
                ? 'Archive+Cleanup finished (limit reached)'
                : 'Archive+Cleanup finished';

            $log = LogRepo::getInstance()->factory()->log()
                ->withTitle('Cron: SyncCleanup')
                ->addAdditionalData('runId', $runId)
                ->addAdditionalData('cutoffUtc', $cutoff)
                ->addAdditionalData('retentionDays', $retentionDays)
                ->addAdditionalData('batchSize', $batchSize)
                ->addAdditionalData('maxBatches', $maxBatches)
                ->addAdditionalData('deleted', $summary->deleted)
                ->addAdditionalData('batches', $summary->batches)
                ->addAdditionalData('stoppedByLimit', $summary->stoppedByLimit)
                ->addAdditionalData('durationMs', $durationMs);

            $log->write($logMessage, $level);

            return $result;

        } catch (\Throwable $e) {
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            $result->setStatus(ilCronJobResult::STATUS_FAIL);
            $result->setMessage("Archive+Cleanup FAIL: " . $this->truncate($e->getMessage(), 320));

            $log = LogRepo::getInstance()->factory()->log()
                ->withTitle('Cron: SyncCleanup')
                ->withLevel(ILog::LEVEL_EXCEPTION)
                ->withMessage('Archive+Cleanup failed: ' . $this->truncate($e->getMessage(), 800))
                ->addAdditionalData('runId', $runId)
                ->addAdditionalData('cutoffUtc', $cutoff)
                ->addAdditionalData('durationMs', $durationMs)
                ->addAdditionalData('retentionDays', $retentionDays)
                ->addAdditionalData('batchSize', $batchSize)
                ->addAdditionalData('maxBatches', $maxBatches)
                ->addAdditionalData('exception', get_class($e))
                ->addAdditionalData('file', $e->getFile())
                ->addAdditionalData('line', $e->getLine())
                ->addAdditionalData('trace', array_slice($e->getTrace(), 0, 20));

            LogRepo::getInstance()->storeLog($log, true);

            return $result;
        }
    }

    private function truncate(string $s, int $max): string
    {
        if ($max < 4) {
            return '';
        }
        return (mb_strlen($s) <= $max) ? $s : (mb_substr($s, 0, $max - 3) . '...');
    }

}