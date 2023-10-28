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

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class MetadataAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MetadataAwareDataTransferObject
{
    /**
     * @var IMetadata[]
     */
    private array $_meta_data = [];

    /**
     * @inheritdoc
     */
    public function addMetadata(IMetadata $IMetadata): IMetadataAwareDataTransferObject
    {
        $this->_meta_data[$IMetadata->getIdentifier()] = $IMetadata;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMetaData(): array
    {
        return is_array($this->_meta_data) ? $this->_meta_data : [];
    }
}
