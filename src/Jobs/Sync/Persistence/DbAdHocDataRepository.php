<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Sync\Persistence;

use ilDBInterface;
use srag\Plugins\Hub2\Exception\CleanupDatabaseException;

final class DbAdHocDataRepository implements AdHocDataRepository
{
    private const TABLE = 'sr_hub2_ad_hoc_data';

    public function __construct(private readonly ilDBInterface $db)
    {
    }

    public function deleteProcessedBefore(string $cutoffUtc, int $limit): int
    {
        try {
            return (int) $this->db->manipulateF(
                "DELETE FROM " . self::TABLE . "
                 WHERE processed_date IS NOT NULL
                   AND processed_date < %s
                 ORDER BY processed_date ASC, id ASC
                 LIMIT " . (int) $limit,
                ['timestamp'],
                [$cutoffUtc]
            );
        } catch (\Throwable $e) {
            throw new CleanupDatabaseException(self::TABLE,$cutoffUtc,$e);
        }
    }
}