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

use srag\Plugins\Hub2\Object\IObject;

interface IObjectStatusTransition
{
    /**
     * Transition from the current final status of the object to the next intermediate status.
     * If the current status is not a final one (e.g. CREATED, UPDATED, OUTDATED, IGNORED...), the
     * same final status is returned.
     * Note that this method returns the new status but does NOT set it on the passed object.
     * @param IObject $object
     * @return int
     * @deprecated
     */
    public function finalToIntermediate(IObject $object): int;
}
