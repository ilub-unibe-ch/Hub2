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

namespace srag\Plugins\Hub2\Metadata;

use ilHub2Plugin;
/**
 * Class Metadata
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Metadata implements IMetadata
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    protected int $identifier = 0;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var int
     */
    protected int $record_id;

    /**
     * Metadata constructor
     * @param int $identifier
     * @param int $record_id
     */
    public function __construct(int $identifier, int $record_id = self::DEFAULT_RECORD_ID)
    {
        $this->identifier = $identifier;
        $this->record_id = $record_id;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value): IMetadata
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setIdentifier(int $identifier): IMetadata
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier(): string
    {
        return (string)$this->identifier;
    }

    /**
     * @inheritdoc
     */
    public function getRecordId(): int
    {
        return $this->record_id;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return json_encode([$this->getIdentifier() => $this->getValue()]);
    }
}
