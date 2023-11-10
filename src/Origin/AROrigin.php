<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Origin;

use ActiveRecord;
use ilHub2Plugin;
use InvalidArgumentException;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;
use srag\Plugins\Hub2\Config\Config;
/**
 * ILIAS ActiveRecord implementation of an Origin
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AROrigin extends ActiveRecord implements IOrigin
{
    public const TABLE_NAME = 'sr_hub2_origin';
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var array
     */
    public static array $object_types = [
        IOrigin::OBJECT_TYPE_USER => IOrigin::OBJECT_TYPE_USER,
        IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP => IOrigin::OBJECT_TYPE_COURSE_MEMBERSHIP,
        IOrigin::OBJECT_TYPE_COURSE => IOrigin::OBJECT_TYPE_COURSE,
        IOrigin::OBJECT_TYPE_CATEGORY => IOrigin::OBJECT_TYPE_CATEGORY,
        IOrigin::OBJECT_TYPE_GROUP => IOrigin::OBJECT_TYPE_GROUP,
        IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP => IOrigin::OBJECT_TYPE_GROUP_MEMBERSHIP,
        IOrigin::OBJECT_TYPE_SESSION => IOrigin::OBJECT_TYPE_SESSION,
        IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP => IOrigin::OBJECT_TYPE_SESSION_MEMBERSHIP,
        IOrigin::OBJECT_TYPE_ORGNUNIT => IOrigin::OBJECT_TYPE_ORGNUNIT,
        IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP => IOrigin::OBJECT_TYPE_ORGNUNIT_MEMBERSHIP
    ];

    public function getConnectorContainerName(): string
    {
        return self::TABLE_NAME;
    }

    public static function returnDbTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @var int
     * @db_has_field          true
     * @db_is_unique          true
     * @db_is_primary         true
     * @db_fieldtype          integer
     * @db_length             8
     * @db_sequence           true
     */
    protected ?int $id = null;
    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           text
     * @db_length              32
     * @db_is_notnull
     */
    protected string $object_type = '';
    /**
     * @var bool
     * @db_has_field           true
     * @db_fieldtype           integer
     * @db_length              1
     */
    protected $active = 0;
    /**
     * @var string
     * @db_has_field           true
     * @db_is_notnull          true
     * @db_fieldtype           text
     * @db_length              2048
     */
    protected string $title = '';
    /**
     * @var string
     * @db_has_field        true
     * @db_fieldtype        text
     * @db_length           2048
     */
    protected string $description = '';
    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           text
     * @db_length              256
     * @db_is_notnull          true
     */
    protected string $implementation_class_name = '';
    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           text
     * @db_length              256
     */
    protected string $implementation_namespace = IOrigin::ORIGIN_MAIN_NAMESPACE;
    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           timestamp
     */
    protected ?string $updated_at = '';
    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           timestamp
     */
    protected string $created_at = '';
    /**
     * @var array
     * @db_has_field        true
     * @db_fieldtype        clob
     * @db_length           4000
     */
    protected array $config = [];
    /**
     * @var array
     * @db_has_field        true
     * @db_fieldtype        clob
     * @db_length           4000
     */
    protected array $properties = [];

    protected ?IOriginConfig $_config = null;
    protected ?IOriginProperties $_properties = null;

    /**
     * @var string
     * @db_has_field           true
     * @db_fieldtype           timestamp
     */
    protected ?string $last_run = "";
    /**
     * @var bool
     */
    protected bool $force_update = false;
    /**
     * @var bool
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected bool $adhoc = false;
    /**
     * @var bool
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     1
     * @con_is_notnull true
     */
    protected bool $adhoc_parent_scope = false;
    /**
     * @var int
     * @db_has_field  true
     * @db_fieldtype  integer
     * @db_length     8
     * @db_is_notnull true
     */
    protected int $sort = 0;

    /**
     *
     */
    public function create(): void
    {
        $this->created_at = date(Config::SQL_DATE_FORMAT);
        $this->setObjectType($this->parseObjectType());

        if (empty($this->sort)) {
            $origins = (new OriginFactory())->getAll();
            if (count($origins) > 0) {
                $this->sort = (end($origins)->getSort() + 1);
            } else {
                $this->sort = 1;
            }
        }

        parent::create();
    }

    /**
     *
     */
    public function update(): void
    {
        $this->updated_at = date(Config::SQL_DATE_FORMAT);
        parent::update();
    }

    /**
     * @inheritdoc
     */
    public function sleep($field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case 'config':
                if ($this->_config === null) {
                    $config = $this->getOriginConfig([]);

                    return json_encode($config->getData());
                } else {
                    return json_encode($this->config()->getData());
                }

                // no break
            case 'properties':
                if ($this->_properties === null) {
                    $properties = $this->getOriginProperties([]);

                    return json_encode($properties->getData());
                } else {
                    return json_encode($this->properties()->getData());
                }

                // no break
            case "adhoc":
            case "adhoc_parent_scope":
                return ($field_value ? 1 : 0);

            default:
                return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'config':
            case 'properties':
                return json_decode($field_value, true);

            case "adhoc":
            case "adhoc_parent_scope":
                return boolval($field_value);

            case "sort":
                return intval($field_value);

            default:
                return null;
        }
    }

    /**
     *
     */
    public function afterObjectLoad(): void
    {
        $this->_config = $this->getOriginConfig($this->getConfigData());
        $this->_properties = $this->getOriginProperties($this->getPropertiesData());
    }

    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    /**
     * @inheritdoc
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImplementationClassName(): string
    {
        return $this->implementation_class_name;
    }

    /**
     * @inheritdoc
     */
    public function setImplementationClassName(string $name): self
    {
        $this->implementation_class_name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getImplementationNamespace(): string
    {
        return $this->implementation_namespace ?: IOrigin::ORIGIN_MAIN_NAMESPACE;
    }

    /**
     * @param string $implementation_namespace
     */
    public function setImplementationNamespace(string $implementation_namespace)
    {
        $this->implementation_namespace = $implementation_namespace;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @inheritdoc
     */
    public function getObjectType(): string
    {
        return $this->object_type;
    }

    /**
     * @return string
     */
    public function getLastRun(): ?string
    {
        return $this->last_run;
    }

    /**
     * @param string $last_run
     */
    public function setLastRun(string $last_run)
    {
        $this->last_run = $last_run;
    }

    /**
     * @inheritdoc
     */
    public function setObjectType(string $type): self
    {
        if (!in_array($type, self::$object_types)) {
            throw new InvalidArgumentException("'$type' is not a valid hub object type");
        }
        $this->object_type = $type;

        return $this;
    }

    public function config(): IOriginConfig
    {
        return $this->_config ?? ($this->_config = $this->getOriginConfig($this->config));
    }

    public function properties(): IOriginProperties
    {
        return $this->_properties ?? ($this->_properties = $this->getOriginProperties($this->properties));
    }

    //	/**
    //	 * @inheritdoc
    //	 */
    //	public function implementation() {
    //		$factory = new OriginImplementationFactory($this);
    //		return $factory->instance();
    //	}

    /**
     * Return the concrete implementation of the IOriginConfig.
     * @param array $data
     * @return IOriginConfig
     */
    abstract protected function getOriginConfig(array $data): IOriginConfig;

    /**
     * Return the concrete implementation of the origin properties.
     * @param array $data
     * @return IOriginProperties
     */
    abstract protected function getOriginProperties(array $data): IOriginProperties;

    /**
     * @return string
     */
    private function parseObjectType(): string
    {
        $out = [];
        preg_match('%AR(.*)Origin$%', get_class($this), $out);

        return lcfirst($out[1]);
    }

    /**
     * @return array
     */
    protected function getConfigData(): array
    {
        return $this->config;
    }

    /**
     * @return array
     */
    protected function getPropertiesData(): array
    {
        return $this->properties;
    }

    /**
     * Run Sync without Hash comparison
     */
    public function forceUpdate()
    {
        $this->force_update = true;
    }

    /**
     * @return bool
     */
    public function isUpdateForced(): bool
    {
        return $this->force_update;
    }

    /**
     * @inheritdoc
     */
    public function isAdHoc(): bool
    {
        return $this->adhoc;
    }

    /**
     * @inheritdoc
     */
    public function setAdHoc(bool $adhoc)/*: void*/
    {
        $this->adhoc = $adhoc;
    }

    /**
     * @inheritdoc
     */
    public function isAdhocParentScope(): bool
    {
        return $this->adhoc_parent_scope;
    }

    /**
     * @inheritdoc
     */
    public function setAdhocParentScope(bool $adhoc_parent_scope)/*: void*/
    {
        $this->adhoc_parent_scope = $adhoc_parent_scope;
    }

    /**
     * @inheritdoc
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @inheritdoc
     */
    public function setSort(int $sort)/*: void*/
    {
        $this->sort = $sort;
    }
}
