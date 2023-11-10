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

namespace srag\Plugins\Hub2\MappingStrategy;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Class MappingStrategyAwareDataTransferObject
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait MappingStrategyAwareDataTransferObject
{
    private ?IMappingStrategy $_mapping_strategy = null;

    /**
     * @inheritdoc
     */
    public function getMappingStrategy(): IMappingStrategy
    {
        return $this->_mapping_strategy ? $this->_mapping_strategy : new None();
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function overrideMappingStrategy(IMappingStrategy $strategy): IDataTransferObject
    {
        $this->_mapping_strategy = $strategy;

        return $this;
    }
}
