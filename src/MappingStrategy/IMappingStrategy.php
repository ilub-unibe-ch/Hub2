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

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IMappingStrategy
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMappingStrategy
{
    /**
     * @param IDataTransferObject $dto
     * @return int ILIAS ID which will be passed to the Processor.
     * Return 0 if no mapping possible, therefore the Object will be created.
     * Return an existing ILIAS ID which leads to an update of the Object
     * @throws HubException
     */
    public function map(IDataTransferObject $dto): int;
}
