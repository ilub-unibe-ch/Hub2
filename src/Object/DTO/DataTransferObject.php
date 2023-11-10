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

namespace srag\Plugins\Hub2\Object\DTO;

use ArrayObject;
use ilHub2Plugin;
use Serializable;

/**
 * Class ObjectDTO
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class DataTransferObject implements IDataTransferObject
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    private string $ext_id;
    private string $period = '';
    private bool $should_deleted = false;
    protected ?string $additionalData = "";

    /**
     * @param string $ext_id
     */
    public function __construct(string $ext_id)
    {
        $this->ext_id = $ext_id;
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
    public function getData(): array
    {
        $data = [];
        foreach ($this->getProperties() as $var) {
            $data[$var] = $this->{$var};
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function setData(array $data): IDataTransferObject
    {
        foreach ($data as $key => $value) {
            if ($key !== "should_deleted") {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getProperties(): array
    {
        return array_filter(array_keys(get_class_vars(get_class($this))), function (string $property): bool {
            return ($property !== "should_deleted");
        });
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', [
            "ext_id: " . $this->getExtId(),
            "period: " . $this->getPeriod(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function shouldDeleted(): bool
    {
        return $this->should_deleted;
    }

    /**
     * @inheritdoc
     */
    public function setShouldDeleted(bool $should_deleted): IDataTransferObject
    {
        $this->should_deleted = $should_deleted;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalData(): Serializable
    {
        $object = unserialize($this->additionalData);
        if (!$object) {
            return unserialize(serialize(new ArrayObject()));
        }

        return $object;
    }

    /**
     * @inheritdoc
     */
    public function withAdditionalData(Serializable $additionalData)
    {
        $this->additionalData = serialize($additionalData);

        return $this;
    }
}
