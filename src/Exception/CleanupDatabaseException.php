<?php

namespace srag\Plugins\Hub2\Exception;

use srag\Plugins\Hub2\Exception\HubException;

class CleanupDatabaseException extends HubException
{

    public function __construct(
        private readonly string $table,
        private readonly string $cutoffUtc,
        \Throwable $previous = null
    )
    {
        parent::__construct(
            "Database cleanup failed for table {$table} (cutoff ={$cutoffUtc}),0,$previous)",
        );
    }

    public function getCutoffUtc(): string
    {
        return $this->cutoffUtc;
    }

    public function getTable(): string
    {
        return $this->table;
    }

}