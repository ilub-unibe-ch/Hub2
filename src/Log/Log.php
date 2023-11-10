<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Log;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilHub2Plugin;
use stdClass;
use srag\Plugins\Hub2\Log\Repository as LogRepository;

/**
 * Class Log
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Log extends ActiveRecord implements ILog
{
    public const TABLE_NAME = "sr_hub2_log";
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected IRepository $log_repo;

    final public function getConnectorContainerName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @deprecated
     */
    final public static function returnDbTableName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @var array
     */
    public static array $levels
        = [
            self::LEVEL_INFO => self::LEVEL_INFO,
            self::LEVEL_WARNING => self::LEVEL_WARNING,
            self::LEVEL_EXCEPTION => self::LEVEL_EXCEPTION,
            self::LEVEL_CRITICAL => self::LEVEL_CRITICAL,
        ];
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected ?int $log_id = null;
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected string $title = "";
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected string $message = "";
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     */
    protected int $status = 0;
    /**
     * @var ilDateTime
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected ilDateTime $date;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected int $level = self::LEVEL_INFO;
    /**
     * @var stdClass
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected stdClass $additional_data;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected ?int $origin_id = null;
    /**
     * @var string
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected string $origin_object_type = "";
    /**
     * @var string|null
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       255
     * @con_is_notnull   false
     */
    protected ?string $object_ext_id = null;
    /**
     * @var int|null
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected ?int $object_ilias_id = null;

    /**
     * Log constructor
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    final public function __construct(?int $primary_key_value = null)
    {
        $this->additional_data = new stdClass();
        $this->log_repo = LogRepository::getInstance();
        parent::__construct($primary_key_value);
    }

    public function getLogId(): ?int
    {
        return $this->log_id;
    }

    public function withLogId(int $log_id): ILog
    {
        $this->log_id = $log_id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function withTitle(string $title): ILog
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function withMessage(string $message): ILog
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ilDateTime
    {
        return $this->date;
    }

    public function withDate(ilDateTime $date): ILog
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function withStatus(int $status): ILog
    {
        $this->status = $status;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function withLevel(int $level): ILog
    {
        $this->level = $level;

        return $this;
    }

    public function getAdditionalData(): stdClass
    {
        return $this->additional_data;
    }

    public function withAdditionalData(stdClass $additional_data): ILog
    {
        $this->additional_data = $additional_data;

        return $this;
    }

    public function addAdditionalData(string $key, $value): ILog
    {
        $this->additional_data->{$key} = $value;

        return $this;
    }

    public function getOriginId(): ?int
    {
        return $this->origin_id;
    }

    public function withOriginId(int $origin_id): ILog
    {
        $this->origin_id = $origin_id;

        return $this;
    }

    public function getOriginObjectType(): string
    {
        return $this->origin_object_type;
    }

    public function withOriginObjectType(string $origin_object_type): ILog
    {
        $this->origin_object_type = $origin_object_type;

        return $this;
    }

    public function getObjectExtId(): ?string
    {
        return $this->object_ext_id;
    }

    public function withObjectExtId(string $object_ext_id = null): ILog
    {
        $this->object_ext_id = $object_ext_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getObjectIliasId(): ?int
    {
        return $this->object_ilias_id;
    }

    /**
     * @inheritdoc
     */
    public function withObjectIliasId(/*?*/
        int $object_ilias_id = null
    ): ILog {
        $this->object_ilias_id = $object_ilias_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function write(string $message, int $level = self::LEVEL_INFO): void
    {
        $this->log_repo->storeLog($this->withMessage($message)->withLevel($level));
    }
}
