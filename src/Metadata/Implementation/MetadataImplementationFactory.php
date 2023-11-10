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
use srag\Plugins\Hub2\Object\Category\CategoryDTO;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\Group\GroupDTO;
use srag\Plugins\Hub2\Object\Session\SessionDTO;
use srag\Plugins\Hub2\Object\User\UserDTO;


/**
 * Class IMetadataImplementationFactory
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataImplementationFactory implements IMetadataImplementationFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function userDefinedField(IMetadata $metadata, string $ilias_id): IMetadataImplementation
    {
        return new UDF($metadata, $ilias_id);
    }

    /**
     * @inheritdoc
     */
    public function customMetadata(IMetadata $metadata, string $ilias_id): IMetadataImplementation
    {
        return new CustomMetadata($metadata, $ilias_id);
    }

    /**
     * @inheritdoc
     */
    public function getImplementationForDTO(
        IMetadataAwareDataTransferObject $dto,
        IMetadata $metadata,
        string $ilias_id
    ): IMetadataImplementation {
        switch (true) {
            case is_a($dto, GroupDTO::class):
            case is_a($dto, CourseDTO::class):
            case is_a($dto, CategoryDTO::class):
            case is_a($dto, SessionDTO::class):
                return $this->customMetadata($metadata, $ilias_id);
            case is_a($dto, UserDTO::class):
                return $this->userDefinedField($metadata, $ilias_id);
        }
        return $this->userDefinedField($metadata, $ilias_id);

    }
}
