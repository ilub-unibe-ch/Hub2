<?php

namespace srag\Plugins\Hub2\Jobs;

use ilCronJobResult;
use ILIAS\Cron\Schedule\CronJobScheduleType;
use srag\Plugins\Hub2\Jobs\Sync\Cleanup\SyncCleanupService;
use srag\Plugins\Hub2\Jobs\Sync\Persistence\DbAdHocDataRepository;
use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\Log\Repository as LogRepo;

class SyncCleanupJob extends \ilCronJob
{
    public const CRON_JOB_ID = "Sync-Cleanup-Job";
    private const RETENTION_DAYS = 7;
    private const BATCH_SIZE = 10;


    public function getId(): string
    {
        return self::CRON_JOB_ID;

    }

    public function getTitle(): string
    {
        return "Sync Cleanup Job";
    }

    public function getDescription(): string
    {
        return "Deletes all sync jobs exceeding a specified retention period";
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

        $maxBatches = 10;

        $cutoff = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))
            ->modify("-" . self::RETENTION_DAYS . "days")
            ->format('Y-m-d H:i:s');

        try {
            $repo = new DbAdHocDataRepository($DIC->database());
            $service = new SyncCleanupService($repo);
            $summary = $service->cleanupProcessedBefore(
                $cutoff,
                self::BATCH_SIZE,
                $maxBatches,
                null
            );
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            $msg = "Cleanup OK: {$summary->deleted} records deleted in {$summary->batches} Batch(es) (cutoff {$cutoff} UTC).";
            if ($summary->stoppedByLimit) {
                $msg .= " Warning: Stopped due to maxBatches limit – the next cron will continue.";
            }

            $result->setStatus(ilCronJobResult::STATUS_OK);
            $result->setMessage($msg);

            $level = $summary->stoppedByLimit ? ILog::LEVEL_WARNING : ILog::LEVEL_INFO;
            $logMessage = $summary->stoppedByLimit ? 'Cleanup finished (limit reached)' : 'Cleanup finished';

            $log = LogRepo::getInstance()->factory()->log()
                ->withTitle('Cron: SyncCleanup')
                ->addAdditionalData('runId', $runId)
                ->addAdditionalData('cutoffUtc', $cutoff)
                ->addAdditionalData('retentionDays', self::RETENTION_DAYS)
                ->addAdditionalData('batchSize', self::BATCH_SIZE)
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
            $result->setMessage("Cleanup FAIL: " . $e->getMessage());

            $log = LogRepo::getInstance()->factory()->exceptionLog($e)
                ->withTitle('Cron: SyncCleanup')
                ->withMessage('Cleanup failed: ' . $e->getMessage())
                ->addAdditionalData('runId', $runId)
                ->addAdditionalData('cutoffUtc', $cutoff)
                ->addAdditionalData('durationMs', $durationMs)
                ->addAdditionalData('retentionDays', defined('self::RETENTION_DAYS') ? self::RETENTION_DAYS : null)
                ->addAdditionalData('batchSize', defined('self::BATCH_SIZE') ? self::BATCH_SIZE : null)
                ->addAdditionalData('maxBatches', $maxBatches);

            LogRepo::getInstance()->storeLog($log, true);
            return $result;
        }
    }

}