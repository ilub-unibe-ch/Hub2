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

namespace srag\Plugins\Hub2\Config;

use ActiveRecord;
use arConnector;
use LogicException;

/**
 * Class Config
 *
 * @package srag\ActiveRecordConfig\Hub2\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecord
{
    /**
     * @var string
     */
    public const SQL_DATE_FORMAT = "Y-m-d H:i:s";
    /**
     * @var int
     */
    public const TYPE_STRING = 1;
    /**
     * @var int
     */
    public const TYPE_INTEGER = 2;
    /**
     * @var int
     */
    public const TYPE_DOUBLE = 3;
    /**
     * @var int
     */
    public const TYPE_BOOLEAN = 4;
    /**
     * @var int
     */
    public const TYPE_TIMESTAMP = 5;
    /**
     * @var int
     */
    public const TYPE_DATETIME = 6;
    /**
     * @var int
     */
    public const TYPE_JSON = 7;
    /**
     * @var string
     */
    protected static string $table_name;


    /**
     * @return string
     */
    public static function getTableName(): string
    {
        if (empty(self::$table_name)) {
            throw new LogicException("table name is empty - please call repository earlier!");
        }

        return self::$table_name;
    }


    /**
     * @param string $table_name
     */
    public static function setTableName(string $table_name): void
    {
        self::$table_name = $table_name;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName(): string
    {
        return self::getTableName();
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName(): string
    {
        return self::getTableName();
    }


    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_length      100
     * @con_is_notnull  true
     * @con_is_primary  true
     */
    protected ?string $name = "";
    /**
     * @var mixed
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  false
     */
    protected $value = null;


    /**
     * Config constructor
     *
     * @param string|null      $primary_name_value
     * @param arConnector|null $connector
     */
    public function __construct(string $primary_name_value = null)
    {
        if($primary_name_value === "0"){
            $primary_name_value = null;
        }
        parent::__construct($primary_name_value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
