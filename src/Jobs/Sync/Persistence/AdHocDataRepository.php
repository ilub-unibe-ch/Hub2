<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Sync\Persistence;

interface AdHocDataRepository
{
    /**
     * @description
     * Deletes records whose processed_date is less than or equal to cutoff.
     * Return the number of records deleted.
     * @author Bahwar Adi <bahwar.adi@unibe.ch>
     */
    public function deleteProcessedBefore(string $cutoffUtc, int $limit): int;
}