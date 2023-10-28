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

namespace srag\Plugins\Hub2\Origin;

/**
 * Interface IOriginFactory
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginFactory
{
    /**
     * Get the concrete origin by ID, e.g. returns a IUserOrigin if the given ID belongs
     * to a origin of object type 'user'.
     * @param int $id
     * @return IOrigin|null
     */
    public function getById(int $id): ?IOrigin; //Correct return type would by : ?IOrigin, but this is PHP7.1+

    /**
     * @param string $type
     * @return IOrigin
     */
    public function createByType(string $type): IOrigin;

    /**
     * @return IOrigin[]
     */
    public function getAllActive(): array;

    /**
     * @return IOrigin[]
     */
    public function getAll(): array;

    /**
     * @param int $origin_id
     */
    public function delete(int $origin_id)/*: void*/
    ;
}
