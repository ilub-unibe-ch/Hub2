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

/**
 * Interface IMetadata
 * @package srag\Plugins\Hub2\Metadata
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IMetadata
{
    public const DEFAULT_RECORD_ID = 1;

    /**
     * @param string $value
     * @return IMetadata
     */
    public function setValue(string $value): IMetadata;

    /**
     * @param int $identifier
     * @return IMetadata
     */
    public function setIdentifier(int $identifier): IMetadata;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @return int
     */
    public function getRecordId(): int;

    /**
     * @return string
     */
    public function __toString(): string;
}
