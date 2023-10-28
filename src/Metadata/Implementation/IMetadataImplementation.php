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

/**
 * Interface IMetadataImplementation
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadataImplementation
{
    /**
     * Reads the Value from the ILIAS representative (UDF od Custom MD)
     * @return void
     */
    public function read();

    /**
     * Writes the Value in the ILIAS representative (UDF od Custom MD)
     * @return void
     */
    public function write();

    /**
     * @return IMetadata
     */
    public function getMetadata(): IMetadata;

    /**
     * @return string
     */
    public function getIliasId(): string;
}
