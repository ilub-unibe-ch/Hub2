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

namespace srag\Plugins\Hub2\Origin\GroupMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\GroupMembership\GroupMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\GroupMembership\GroupMembershipProperties;

/**
 * Class ARGroupMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\GroupMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARGroupMembershipOrigin extends AROrigin implements IGroupMembershipOrigin
{

    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data): GroupMembershipOriginConfig
    {
        return new GroupMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): GroupMembershipProperties
    {
        return new GroupMembershipProperties($data);
    }
}
