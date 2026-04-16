<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Result;

final class CleanupSummary
{
    public function __construct(
        public readonly int $deleted,
        public readonly int $batches,
        public readonly bool $stoppedByLimit
    ) {
    }
}