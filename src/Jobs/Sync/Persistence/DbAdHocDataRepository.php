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

    public function archiveAndDeleteProcessedBefore(string $cutoffUtc, int $limit): int
    {
        try {
            $this->db->manipulate(
                "LOCK TABLES " . self::SOURCE_TABLE . " WRITE, " . self::ARCHIVE_TABLE . " WRITE"
            );

            try {
                $this->db->manipulateF(
                    "INSERT IGNORE INTO " . self::ARCHIVE_TABLE . " (id, xml_data, delivery_date, pickup_date, processed_date)
                     SELECT id, xml_data, delivery_date, pickup_date, processed_date
                     FROM " . self::SOURCE_TABLE . "
                     WHERE processed_date IS NOT NULL
                       AND processed_date < %s
                     ORDER BY processed_date ASC, id ASC
                     LIMIT %s",
                    ['timestamp', 'integer'],
                    [$cutoffUtc, (int) $limit]
                );

                $deleted = (int) $this->db->manipulateF(
                    "DELETE FROM " . self::SOURCE_TABLE . "
                     WHERE processed_date IS NOT NULL
                       AND processed_date < %s
                     ORDER BY processed_date ASC, id ASC
                     LIMIT %s",
                    ['timestamp', 'integer'],
                    [$cutoffUtc, (int) $limit]
                );
            } finally {
                $this->db->manipulate("UNLOCK TABLES");
            }

            return $deleted;
        } catch (\Throwable $e) {
            throw new CleanupDatabaseException(self::SOURCE_TABLE, $cutoffUtc, $e);
        }
    }
}