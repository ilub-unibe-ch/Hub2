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

namespace srag\Plugins\Hub2\Origin\Config\OrgUnit;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface IOrgUnitOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitOriginConfig extends IOriginConfig
{
    /**
     * @var string
     */
    public const REF_ID_IF_NO_PARENT_ID = "ref_id_if_no_parent_id";

    /**
     * @return int
     */
    public function getRefIdIfNoParentId(): int;
}
