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


use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\UI\CustomView\BaseCustomViewGUI;

/**
 * Class CustomViewGUI
 * @package srag\Plugins\Hub2\UI\CustomView
 * @author  Timon Amstutz
 */
class hub2CustomViewGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected \ILIAS\DI\UIServices $ui;

    /**
     * hub2CustomViewGUI constructor
     */
    public function __construct()
    {
        global $DIC;

        $this->ui = $DIC->ui();
    }

    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        try {
            $class_path = ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_PATH);
            if (!file_exists($class_path)) {
                throw new Exception("File " . $class_path . " doest not Exist");
            }
            require_once $class_path;

            $class_name = ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_CLASS);
            if (!class_exists($class_name)) {
                throw new Exception("Class " . $class_name . " not found. Note that namespaces need to be entered completely");
            }

            $class = new $class_name($this);
            if (!($class instanceof BaseCustomViewGUI)) {
                throw new Exception("Class " . $class_name . " is not an instance of BaseCustomViewGUI");
            }
            $class->executeCommand();
        } catch (Throwable $e) {
            throw $e;
            $this->ui->mainTemplate()->setOnScreenMessage('info', ilHub2Plugin::getInstance()->txt("admin_custom_view_class_not_found_1") . " '"
                . ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_PATH) . "' " . ilHub2Plugin::getInstance()->txt("admin_custom_view_class_not_found_2")
                . " Error: " . $e->getMessage());
        }
    }
}
