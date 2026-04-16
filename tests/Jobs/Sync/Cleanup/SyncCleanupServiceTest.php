<?php
declare(strict_types=1);

namespace Jobs\Sync\Cleanup;

use PHPUnit\Framework\TestCase;
use srag\Plugins\Hub2\Jobs\Sync\Cleanup\SyncCleanupService;
use srag\Plugins\Hub2\Jobs\Sync\Persistence\AdHocDataRepository;

final class SyncCleanupServiceTest extends TestCase
{
    public function testNoWorkIfRepoReturnsZeroImmediately(): void
    {
        $repo = new class implements AdHocDataRepository {
            public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
            {
                return 0;
            }
        };

        $pings = 0;
        $ping = function () use (&$pings): void {
            $pings++;
        };

        $service = new SyncCleanupService($repo);
        $summary = $service->cleanupProcessedBefore('2026-01-01 00:00:00', 5000, 10, $ping);

        $this->assertSame(0, $summary->deleted);
        $this->assertSame(0, $summary->batches);
        $this->assertFalse($summary->stoppedByLimit);
        $this->assertSame(0, $pings);
    }

    public function testStopsWhenRepoReturnsZero(): void
    {
        $repo = new class implements AdHocDataRepository {
            private array $returns = [5000, 120, 0];

            public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
            {
                return array_shift($this->returns) ?? 0;
            }
        };

        $service = new SyncCleanupService($repo);

        $summary = $service->cleanupProcessedBefore('2026-01-01 00:00:00', 5000, 10, null);

        $this->assertSame(5120, $summary->deleted);
        $this->assertSame(2, $summary->batches);
        $this->assertFalse($summary->stoppedByLimit);
    }

    public function testStopsByLimit(): void
    {
        $repo = new class implements AdHocDataRepository {
            public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
            {
                return 1;
            }
        };

        $service = new SyncCleanupService($repo);

        $summary = $service->cleanupProcessedBefore('2026-01-01 00:00:00', 5000, 3, null);

        $this->assertSame(3, $summary->deleted);
        $this->assertSame(3, $summary->batches);
        $this->assertTrue($summary->stoppedByLimit);
    }

    public function testMaxBatchesOneStopsAfterOneBatch(): void
    {
        $repo = new class implements AdHocDataRepository {
            public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
            {
                return 10;
            }
        };

        $service = new SyncCleanupService($repo);
        $summary = $service->cleanupProcessedBefore('2026-01-01 00:00:00', 5000, 1, null);

        $this->assertSame(10, $summary->deleted);
        $this->assertSame(1, $summary->batches);
        $this->assertTrue($summary->stoppedByLimit);
    }


    public function testPingIsCalledPerBatch(): void
    {
        $repo = new class implements AdHocDataRepository {
            private array $returns = [2, 1, 0];

            public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
            {
                return array_shift($this->returns) ?? 0;
            }
        };

        $pings = 0;
        $ping = function () use (&$pings): void {
            $pings++;
        };

        $service = new SyncCleanupService($repo);

        $summary = $service->cleanupProcessedBefore('2026-01-01 00:00:00', 5000, 10, $ping);

        $this->assertSame(3, $summary->deleted);
        $this->assertSame(2, $summary->batches);
        $this->assertSame(2, $pings);
        $this->assertFalse($summary->stoppedByLimit);
    }
}
