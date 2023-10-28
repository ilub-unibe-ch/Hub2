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

namespace srag\Plugins\Hub2\UI;

use ilHub2Plugin;

use srag\Plugins\Hub2\Origin\AROrigin;


/**
 * Class OriginFormFactory
 * @package srag\Plugins\Hub2\UI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginFormFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @param AROrigin $origin
     * @return string
     */
    public function getFormClassNameByOrigin(AROrigin $origin): string
    {
        $type = $origin->getObjectType();

        $ucfirst = ucfirst($type);

        return "srag\\Plugins\\Hub2\\UI\\" . $ucfirst . "\\" . $ucfirst . "OriginConfigFormGUI";
    }
}
