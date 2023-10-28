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

namespace srag\Plugins\Hub2\Origin\Properties\OrgUnit;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitProperties
 * @package srag\Plugins\Hub2\Origin\Properties\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitProperties extends IOriginProperties
{
    /**
     * @var string
     */
    public const PROP_DESCRIPTION = "description";
    /**
     * @var string
     */
    public const PROP_EXT_ID = "ext_id";
    /**
     * @var string
     */
    public const PROP_ORG_UNIT_TYPE = "org_unit_type";
    /**
     * @var string
     */
    public const PROP_OWNER = "owner";
    /**
     * @var string
     */
    public const PROP_PARENT_ID = "parent_id";
    /**
     * @var string
     */
    public const PROP_PARENT_ID_TYPE = "parent_id_type";
    /**
     * @var string
     */
    public const PROP_TITLE = "title";
}
