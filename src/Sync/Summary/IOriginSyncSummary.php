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

namespace srag\Plugins\Hub2\Sync\Summary;

use srag\Plugins\Hub2\Sync\IOriginSync;

/**
 * Interface IOriginSyncSummary
 * @package srag\Plugins\Hub2\Sync\Summary
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOriginSyncSummary
{
    /**
     * @return string The Output of all IOriginSyncs
     */
    public function getOutputAsString(): string;

    /**
     * @param IOriginSync $originSync add another already ran IOriginSync
     */
    public function addOriginSync(IOriginSync $originSync);

    /**
     * @return void
     */
    public function sendEmail();
}
