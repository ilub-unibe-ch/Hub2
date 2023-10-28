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

namespace srag\Plugins\Hub2\Origin\Session;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Session\SessionOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Session\SessionProperties;

/**
 * Class ARSessionOrigin
 * @package srag\Plugins\Hub2\Origin\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARSessionOrigin extends AROrigin implements ISessionOrigin
{

    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data)
    {
        return new SessionOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data)
    {
        return new SessionProperties($data);
    }
}
