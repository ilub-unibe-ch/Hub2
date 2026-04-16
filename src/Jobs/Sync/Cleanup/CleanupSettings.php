<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Sync\Cleanup;

use srag\Plugins\Hub2\Config\ArConfig;

final class CleanupSettings
{
    public const MIN_RETENTION_DAYS = 1;
    public const MAX_RETENTION_DAYS = 30;

    public const MIN_BATCH_SIZE = 1;
    public const MAX_BATCH_SIZE = 500;

    public const MIN_MAX_BATCHES = 1;
    public const MAX_MAX_BATCHES = 10;

    public function getRetentionDays(): int
    {
        return $this->clamp(
            (int) ArConfig::getField(ArConfig::KEY_CLEANUP_RETENTION_DAYS),
            self::MIN_RETENTION_DAYS,
            self::MAX_RETENTION_DAYS
        );
    }

    public function getBatchSize(): int
    {
        return $this->clamp(
            (int) ArConfig::getField(ArConfig::KEY_CLEANUP_BATCH_SIZE),
            self::MIN_BATCH_SIZE,
            self::MAX_BATCH_SIZE
        );
    }

    public function getMaxBatches(): int
    {
        return $this->clamp(
            (int) ArConfig::getField(ArConfig::KEY_CLEANUP_MAX_BATCHES),
            self::MIN_MAX_BATCHES,
            self::MAX_MAX_BATCHES
        );
    }

    public function setRetentionDays(int $days): void
    {
        ArConfig::setField(ArConfig::KEY_CLEANUP_RETENTION_DAYS, $this->clamp($days, self::MIN_RETENTION_DAYS, self::MAX_RETENTION_DAYS));
    }

    public function setBatchSize(int $size): void
    {
        ArConfig::setField(ArConfig::KEY_CLEANUP_BATCH_SIZE, $this->clamp($size, self::MIN_BATCH_SIZE, self::MAX_BATCH_SIZE));
    }

    public function setMaxBatches(int $max): void
    {
        ArConfig::setField(ArConfig::KEY_CLEANUP_MAX_BATCHES, $this->clamp($max, self::MIN_MAX_BATCHES, self::MAX_MAX_BATCHES));
    }

    private function clamp(int $value, int $min, int $max): int
    {
        return max($min, min($max, $value));
    }
}