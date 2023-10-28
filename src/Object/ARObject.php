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

namespace srag\Plugins\Hub2\Object;

use ActiveRecord;
use DateTime;
use Exception;
use ilHub2Plugin;
use InvalidArgumentException;

use srag\Plugins\Hub2\Metadata\Metadata;
use srag\Plugins\Hub2\Taxonomy\ITaxonomy;
use srag\Plugins\Hub2\Taxonomy\Node\Node;
use srag\Plugins\Hub2\Taxonomy\Taxonomy;

use srag\ActiveRecordConfig\Hub2\Config\Config;

/**
 * Class ARObject
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class ARObject extends ActiveRecord implements IObject
{
    /**
     * @abstract
     */
    public const TABLE_NAME = '';
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @return string
     */
    public function getConnectorContainerName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @return string
     * @deprecated
     */
    public static function returnDbTableName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @var array
     */
    public static array $available_status = [
        IObject::STATUS_NEW => "new",
        IObject::STATUS_TO_CREATE => "to_create",
        IObject::STATUS_CREATED => "created",
        IObject::STATUS_UPDATED => "updated",
        IObject::STATUS_TO_UPDATE => "to_update",
        IObject::STATUS_TO_OUTDATED => "to_outdated",
        IObject::STATUS_OUTDATED => "outdated",
        IObject::STATUS_TO_RESTORE => "to_restore",
        IObject::STATUS_IGNORED => "ignored",
        IObject::STATUS_FAILED => "failed"
    ];
    /**
     * The primary ID is a composition of the origin-ID and ext_id
     * @var string
     * @db_has_field    true
     * @db_is_primary   true
     * @db_fieldtype    text
     * @db_length       255
     */
    protected string $id;
    /**
     * @var int
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_is_notnull   true
     * @db_length       8
     * @db_index        true
     */
    protected int $origin_id;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     * @db_index        true
     */
    protected string $ext_id = '';
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected string $delivery_date;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected string $processed_date;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       256
     */
    protected string $ilias_id;
    /**
     * @var int
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     * @db_index        true
     */
    protected int $status = IObject::STATUS_NEW;
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     */
    protected string $period = '';
    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       512
     */
    protected string $hash_code;
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected array $data = [];

    /**
     * @inheritdoc
     */
    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'data':
                return json_encode($this->getData());
            case "meta_data":
                /**
                 * @var IMetadataAwareObject $this
                 */
                $metadataObjects = [];
                $metadata = $this->getMetaData();
                foreach ($metadata as $metadatum) {
                    $metadataObjects[$metadatum->getIdentifier()] = $metadatum->getValue();
                }

                return json_encode($metadataObjects);
            case "taxonomies":
                /**
                 * @var ITaxonomyAwareObject $this
                 */
                $taxonomyObjects = [];
                $taxonomies = $this->getTaxonomies();
                foreach ($taxonomies as $tax) {
                    $nodes = [];
                    foreach ($tax->getNodes() as $node) {
                        $nodes[] = $node->getTitle();
                    }
                    $taxonomyObjects[$tax->getTitle()] = $nodes;
                }

                return json_encode($taxonomyObjects);
        }

        return parent::sleep($field_name);
    }

    /**
     * @inheritdoc
     */
    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'data':
                $data = json_decode($field_value, true);
                if (!is_array($data)) {
                    $data = [];
                }

                return $data;
            case 'meta_data':
                if (is_null($field_value)) {
                    return [];
                }
                $json_decode = json_decode($field_value, true);
                $IMetadata = [];
                if (is_array($json_decode)) {
                    foreach ($json_decode as $key => $value) {
                        $IMetadata[] = (new Metadata($key))->setValue($value);
                    }
                }

                return $IMetadata;
            case 'taxonomies':
                if (is_null($field_value)) {
                    return [];
                }
                $json_decode = json_decode($field_value, true);
                $taxonomies = [];
                foreach ($json_decode as $tax_title => $nodes) {
                    $taxonomy = new Taxonomy($tax_title, ITaxonomy::MODE_CREATE);
                    foreach ($nodes as $node) {
                        $taxonomy->attach(new Node($node));
                    }
                    $taxonomies[] = $taxonomy;
                }

                return $taxonomies;
        }

        return parent::wakeUp($field_name, $field_value);
    }

    public function update(): void
    {
        $this->hash_code = $this->computeHashCode();
        parent::update();
    }

    public function create(): void
    {
        if (!$this->origin_id) {
            throw new Exception("Origin-ID is missing, cannot construct the primary key");
        }
        if (!$this->ext_id) {
            throw new Exception("External-ID is missing");
        }
        $this->id = $this->origin_id . $this->ext_id;
        $this->hash_code = $this->computeHashCode();
        parent::create();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId(string $id): self
    {
        $this->ext_id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDeliveryDate(): DateTime
    {
        return new DateTime($this->delivery_date);
    }

    /**
     * @inheritdoc
     */
    public function getProcessedDate(): DateTime
    {
        return new DateTime($this->processed_date);
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryDate(int $unix_timestamp): self
    {
        $this->delivery_date = date(Config::SQL_DATE_FORMAT, $unix_timestamp);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setProcessedDate(int $unix_timestamp): self
    {
        $this->processed_date = date(Config::SQL_DATE_FORMAT, $unix_timestamp);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getILIASId(): string
    {
        return $this->ilias_id;
    }

    /**
     * @inheritdoc
     */f
    public function setILIASId(string $id): self
    {
        $this->ilias_id = $id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(int $status): self
    {
        if (!isset(self::$available_status[$status])) {
            throw new InvalidArgumentException("'{$status}' is not a valid status");
        }

        /**
         * Omit extensive logs
         *self::logs()->originLog((new OriginFactory())->getById($this->origin_id), $this)->write("Changed status from "
         * . self::$available_status[$this->status] . " to " . self::$available_status[$status]);
         **/

        $this->status = $status;

        return $this;
    }

    /**
     * @param int $origin_id
     * @return $this
     */
    public function setOriginId(int $origin_id): ARObject
    {
        $this->origin_id = $origin_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPeriod(): string
    {
        return $this->period;
    }

    /**
     * @inheritdoc
     */
    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function computeHashCode(): string
    {
        $hash = '';
        foreach ($this->data as $property => $value) {
            $hash .= (is_array($value)) ? implode('', $value) : (string) $value;
        }

        if (isset($this->meta_data)) {
            foreach ($this->meta_data as $property => $value) {
                $hash .= (is_array($value)) ? implode('', $value) : (string) $value;
            }
        }

        if (isset($this->taxonomies)) {
            foreach ($this->taxonomies as $property => $value) {
                $hash .= (is_array($value)) ? implode('', $value) : (string) $value;
            }
        }

        return md5($hash); // TODO: Use other not depcreated, safer hash algo (Like `hash("sha256", $hash)`). But this will cause update all origins!
    }

    /**
     * @inheritdoc
     */
    public function getHashCode(): string
    {
        return $this->hash_code;
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        if (isset($data['period'])) {
            $this->period = $data['period'];
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', [
            "origin_id: " . $this->origin_id,
            "type: " . get_class($this),
            "ext_id: " . $this->getExtId(),
            "ilias_id: " . $this->getILIASId(),
            "status: " . $this->getStatus(),
        ]);
    }
}
