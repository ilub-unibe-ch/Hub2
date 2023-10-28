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

use srag\Plugins\Hub2\UI\Config\ConfigFormGUI;

/**
 * Class ConfigGUI
 * @package srag\Plugins\Hub2\UI\Config
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2ConfigGUI extends hub2MainGUI
{
    public const CMD_SAVE_CONFIG = 'saveConfig';
    public const CMD_CANCEL = 'cancel';

    /**
     * @return ConfigFormGUI
     */
    protected function getConfigForm(): ConfigFormGUI
    {
        return new ConfigFormGUI($this);
    }

    /**
     *
     */
    protected function index()/*: void*/
    {
        $form = $this->getConfigForm();
        self::output()->output($form);
    }

    /**
     *
     */
    protected function saveConfig()/*: void*/
    {
        $form = $this->getConfigForm();

        if ($form->checkInput()) {
            $form->updateConfig();
            ilUtil::sendSuccess(ilHub2Plugin::getInstance()->txt('msg_successfully_saved'), true);
            $this-ctrl->redirect($this);
        }
        $form->setValuesByPost();
        self::output()->output($form);
    }

    /**
     *
     */
    protected function initTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_PLUGIN_CONFIG);
    }
}
