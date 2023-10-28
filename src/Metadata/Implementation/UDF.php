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

use ilUserDefinedData;

/**
 * Class CustomMetadata
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UDF extends AbstractImplementation implements IMetadataImplementation
{
    public const PREFIX = 'f_';

    /**
     * @inheritdoc
     */
    public function write()
    {
        $user_id = $this->getIliasId();
        $ilUserDefinedData = new ilUserDefinedData((int)$user_id);
        $value = $this->getMetadata()->getValue();
        $field_id = $this->getMetadata()->getIdentifier();
        $field_id = self::PREFIX . str_replace(self::PREFIX, '', (string) $field_id);
        $ilUserDefinedData->set($field_id, $value);
        $ilUserDefinedData->update();
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        // no need for a read-Method since wo have to update them anyways due to performance-issues when reading all udfs everytime
    }
}
