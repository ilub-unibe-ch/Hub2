<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

/**
 * Class ilHub2ConfigGUI
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilHub2ConfigGUI extends ilPluginConfigGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    public function performCommand($cmd): void
    {
        global $DIC;;

        switch ($DIC->ctrl()->getNextClass()) {
            case strtolower(hub2MainGUI::class):
                $h = new hub2MainGUI();
                $DIC->ctrl()->forwardCommand($h);
                break;

            default:
                $DIC->ctrl()->redirectByClass([hub2MainGUI::class]);
                break;
        }
    }
}
