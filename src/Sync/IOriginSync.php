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

namespace srag\Plugins\Hub2\Sync;

use srag\Plugins\Hub2\Origin\IOrigin;
use Throwable;

/**
 * Interface IOriginSync
 * @package srag\Plugins\Hub2\Sync
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSync
{
    /**
     * Execute the synchronization for the origin
     * @throws Throwable
     */
    public function execute();

    /**
     * Get the number of objects processed by the final status, e.g.
     *  * IObject::STATUS_CREATED: Number of objects created
     *  * IObject::STATUS_UPDATED: Number of objects updated
     *  * IObject::STATUS_OUTDATED: Number of objects deleted
     *  * IObject::STATUS_IGNORED: Number of objects ignored
     * @param int $status
     * @return int
     */
    public function getCountProcessedByStatus(int $status): int;

    /**
     * Get the number of objects processed by the sync.
     * @return int
     */
    public function getCountProcessedTotal(): int;

    /**
     * Get the amount of delivered data (excludes non-valid data).
     * @return int
     */
    public function getCountDelivered(): int;

    /**
     * Return the current origin
     * @return IOrigin
     */
    public function getOrigin(): IOrigin;
}
