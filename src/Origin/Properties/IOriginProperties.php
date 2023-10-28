<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Origin\Properties;

/**
 * Interface Properties
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginProperties
{
    public const PREFIX_UPDATE_DTO = 'update_dto_';

    /**
     * Get a property value by key, returns NULL if no property is found.
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Checks if the given property of a DTO object should be updated on the ILIAS object,
     * e.g. the first- or lastname of a user.
     * @param string $property
     * @return bool
     */
    public function updateDTOProperty(string $property): bool;

    /**
     * Get all properties as associative array
     * @return array
     */
    public function getData(): array;

    /**
     * Set all properties as associative array
     * @param array $data
     * @return $this
     */
    public function setData(array $data): IOriginProperties;
}
