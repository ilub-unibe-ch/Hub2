<?php
declare(strict_types=1);

namespace srag\Plugins\Hub2\Jobs\Sync\Persistence;

use ilDBInterface;
use srag\Plugins\Hub2\Exception\CleanupDatabaseException;

final class DbAdHocDataRepository implements AdHocDataRepository
{
    private const SOURCE_TABLE  = 'sr_hub2_ad_hoc_data';
    private const ARCHIVE_TABLE = 'sr_hub2_ad_hoc_data_archive';

    public function __construct(private readonly ilDBInterface $db)
    {
    }

    /**
     * @throws CleanupDatabaseException
     */
    public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
    {
        try {
            // 1) Determine IDs
            $set = $this->db->queryF(
                "SELECT id
             FROM ".self::SOURCE_TABLE."
             WHERE processed_date IS NOT NULL
               AND processed_date < %s
             ORDER BY processed_date ASC, id ASC
             LIMIT " . (int) $limit,
                ['timestamp'],
                [$cutoffUtc]
            );

            $ids = [];
            while ($row = $this->db->fetchAssoc($set)) {
                $ids[] = (int) $row['id'];
            }

            if ($ids === []) {
                return 0;
            }

            $in = $this->db->in('id', $ids, false, 'integer');

            // 2) Archive
            $this->db->manipulate(
                query: "INSERT IGNORE INTO ".self::ARCHIVE_TABLE." (id, xml_data, delivery_date, pickup_date, processed_date)
             SELECT id, xml_data, delivery_date, pickup_date, processed_date
             FROM ".self::SOURCE_TABLE."
             WHERE {$in}"
            );

            // 3) DELETE
            $this->db->manipulate(
                "DELETE FROM ".self::SOURCE_TABLE."
             WHERE {$in}
               AND EXISTS (
                   SELECT 1
                   FROM ".self::ARCHIVE_TABLE." a
                   WHERE a.id = ".self::SOURCE_TABLE.".id
               )"
            );

            // 4) deleted = total - remaining
            $cntSet = $this->db->query(
                "SELECT COUNT(*) AS c
             FROM ".self::SOURCE_TABLE."
             WHERE {$in}"
            );
            $cntRow = $this->db->fetchAssoc($cntSet);
            $remaining = (int) ($cntRow['c'] ?? 0);

            return count($ids) - $remaining;

        } catch (\Throwable $e) {
            throw new CleanupDatabaseException(self::SOURCE_TABLE, $cutoffUtc, $e);
        }
    }
}