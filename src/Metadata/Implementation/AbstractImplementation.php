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

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilHub2Plugin;

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class CustomMetadata
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractImplementation implements IMetadataImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var string
     */
    private string $ilias_id;
    /**
     * @var IMetadata
     */
    private IMetadata $metadata;

    /**
     * UDF constructor
     * @param IMetadata $metadata
     */
    public function __construct(IMetadata $metadata, string $ilias_id)
    {
        $this->metadata = $metadata;
        $this->ilias_id = $ilias_id;
    }

    /**
     * @inheritdoc
     */
    abstract public function write();

    /**
     * @inheritdoc
     */
    abstract public function read();

    /**
     * @inheritdoc
     */
    public function getMetadata(): IMetadata
    {
        return $this->metadata;
    }

    /**
     * @inheritdoc
     */
    public function getIliasId(): string
    {
        return $this->ilias_id;
    }
}
