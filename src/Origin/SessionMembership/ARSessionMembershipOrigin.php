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

namespace srag\Plugins\Hub2\Origin\SessionMembership;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\SessionMembership\SessionMembershipOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\SessionMembership\SessionMembershipProperties;

/**
 * Class ARSessionMembershipOrigin
 * @package srag\Plugins\Hub2\Origin\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionMembershipOrigin extends AROrigin implements ISessionMembershipOrigin
{

    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data): SessionMembershipOriginConfig
    {
        return new SessionMembershipOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): SessionMembershipProperties
    {
        return new SessionMembershipProperties($data);
    }
}
