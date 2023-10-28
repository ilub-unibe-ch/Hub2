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
use ilHub2Plugin;

/**
 * Class ArConfigOld
 * @package srag\Plugins\Hub2\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ArConfigOld extends ActiveRecord
{
    /**
     * @var string
     * @deprecated
     */
    public const TABLE_NAME = 'sr_hub2_config';
    /**
     * @var string
     * @deprecated
     */
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @deprecated
     */
    public function getConnectorContainerName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @deprecated
     */
    public static function returnDbTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       64
     * @db_is_primary   true
     * @var string
     * @deprecated
     */
    protected string $identifier;
    /**
     * @db_has_field    true
     * @db_fieldtype    clob
     * @var string
     * @deprecated
     */
    protected string $value;

    /**
     * Get a config value by key.
     * @param string $key
     * @return mixed
     * @deprecated
     */
    public static function getValueByKey(string $key)
    {
        /** @var ARConfig $config */
        $config = self::find($key);

        return ($config) ? $config->getValue() : null;
    }

    /**
     * @param string $key
     * @return ArConfigOld
     * @deprecated
     */
    public static function getInstanceByKey(string $key): ArConfigOld
    {
        $instance = self::find($key);
        if ($instance === null) {
            $instance = new self();
            $instance->setKey($key);
        }

        return $instance;
    }

    /**
     * Encode array data as JSON in database
     * @param string $field_name
     * @return false|string|null
     * @deprecated
     */
    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'value':
                return (is_array($this->value)) ? json_encode($this->value) : $this->value;
        }

        return parent::sleep($field_name);
    }

    /**
     * @return string
     * @deprecated
     */
    public function getKey(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $key
     * @deprecated
     */
    public function setKey(string $key)
    {
        $this->identifier = $key;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @deprecated
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
