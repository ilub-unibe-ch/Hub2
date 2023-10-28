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

namespace srag\Plugins\Hub2\Origin\OrgUnitMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\OrgUnitMembership\IOrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Config\OrgUnitMembership\OrgUnitMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\IOrgUnitMembershipProperties;
use srag\Plugins\Hub2\Origin\Properties\OrgUnitMembership\OrgUnitMembershipProperties;

/**
 * Class AROrgUnitMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AROrgUnitMembershipOrigin extends AROrigin implements IOrgUnitMembershipOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data): IOrgUnitMembershipOriginConfig
    {
        return new OrgUnitMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): IOrgUnitMembershipProperties
    {
        return new OrgUnitMembershipProperties($data);
    }

    /**
     * @inheritdoc
     */
    public function config(): IOrgUnitMembershipOriginConfig
    {
        return parent::config();
    }

    /**
     * @inheritdoc
     */
    public function properties(): IOrgUnitMembershipProperties
    {
        return parent::properties();
    }
}
