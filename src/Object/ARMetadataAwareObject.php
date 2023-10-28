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

use srag\Plugins\Hub2\Metadata\IMetadata;

/**
 * Class ARMetadataAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARMetadataAwareObject
{
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected array $meta_data = [];

    /**
     * @return IMetadata[]
     */
    public function getMetaData(): array
    {
        return is_array($this->meta_data) ? $this->meta_data : [];
    }

    /**
     * @param array $meta_data
     */
    public function setMetaData(array $meta_data)
    {
        $this->meta_data = $meta_data;
    }
}
