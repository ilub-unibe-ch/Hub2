<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Sync\Cleanup;

use srag\Plugins\Hub2\Jobs\Result\CleanupSummary;
use srag\Plugins\Hub2\Jobs\Sync\Persistence\AdHocDataRepository;

final class SyncCleanupService
{
    public function __construct(
        private readonly AdHocDataRepository $repo
    ) {
    }

    /**
     * @return CleanupSummary of deleted records
     */
    public function cleanupProcessedBefore(
        string    $cutoffUtc,
        int       $batchSize,
        int       $maxBatches = 200,
        ?callable $ping = null
    ): CleanupSummary
    {
        $total = 0;
        $batches = 0;

        while ($batches < $maxBatches) {
            $deleted = $this->repo->deleteProcessedBefore($cutoffUtc, $batchSize);

            if ($deleted === 0) {
                return new CleanupSummary($total, $batches, false);
            }

            $total += $deleted;
            $batches++;

            if ($ping !== null) {
                $ping();
            }
        }

        return new CleanupSummary($total, $batches, true);
    }
}
