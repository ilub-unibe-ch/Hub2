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

use srag\Plugins\Hub2\Metadata\IMetadata;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;

/**
 * Class IMetadataImplementationFactory
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementationFactory
{
    /**
     * @param IMetadata $metadata
     * @param int       $ilias_id
     * @return IMetadataImplementation
     */
    public function userDefinedField(IMetadata $metadata, string $ilias_id): IMetadataImplementation;

    /**
     * @param IMetadata $metadata
     * @param int       $ilias_id
     * @return IMetadataImplementation
     */
    public function customMetadata(IMetadata $metadata, string $ilias_id): IMetadataImplementation;

    /**
     * @param IMetadataAwareDataTransferObject $dto
     * @param IMetadata                        $metadata
     * @param int                              $ilias_id
     * @return IMetadataImplementation
     */
    public function getImplementationForDTO(
        IMetadataAwareDataTransferObject $dto,
        IMetadata $metadata,
        string $ilias_id
    ): IMetadataImplementation;
}
