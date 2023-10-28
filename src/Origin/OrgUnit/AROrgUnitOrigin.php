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

namespace srag\Plugins\Hub2\Origin\OrgUnit;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\OrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\IOrgUnitProperties;
use srag\Plugins\Hub2\Origin\Properties\OrgUnit\OrgUnitProperties;

/**
 * Class AROrgUnitOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnitOrigin extends AROrigin implements IOrgUnitOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data): IOrgUnitOriginConfig
    {
        return new OrgUnitOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): IOrgUnitProperties
    {
        return new OrgUnitProperties($data);
    }

    /**
     * @inheritdoc
     */
    public function config(): IOrgUnitOriginConfig
    {
        return parent::config();
    }

    /**
     * @inheritdoc
     */
    public function properties(): IOrgUnitProperties
    {
        return parent::properties();
    }
}
