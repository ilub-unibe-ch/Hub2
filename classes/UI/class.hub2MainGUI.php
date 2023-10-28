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
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\Origin\OriginRepository;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;


/**
 * Class MainGUI
 * @package           srag\Plugins\Hub2\UI
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @ilCtrl_IsCalledBy hub2MainGUI: ilHub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigOriginsGUI
 * @ilCtrl_calls      hub2MainGUI: hub2ConfigGUI
 * @ilCtrl_calls      hub2MainGUI: hub2CustomViewGUI
 * @ilCtrl_Calls      hub2MainGUI: ilPropertyFormGUI
 */
class hub2MainGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const TAB_PLUGIN_CONFIG = 'tab_plugin_config';
    public const TAB_ORIGINS = 'tab_origins';
    public const TAB_CUSTOM_VIEWS = 'admin_tab_custom_views';
    public const CMD_INDEX = 'index';

    /**
     * MainGUI constructor
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->initTabs();
        $nextClass = $this->ctrl->getNextClass();

        switch ($nextClass) {
            case strtolower(hub2ConfigGUI::class):
                $this->ctrl->forwardCommand(new hub2ConfigGUI());
                break;
            case strtolower(hub2ConfigOriginsGUI::class):
                $this->ctrl->forwardCommand(new hub2ConfigOriginsGUI());
                break;
            case strtolower(hub2CustomViewGUI::class):
                self::dic()->tabs()->activateTab(self::TAB_CUSTOM_VIEWS);
                $this->ctrl->forwardCommand(new hub2CustomViewGUI());
                break;
            case strtolower(hub2DataGUI::class):
            case strtolower(hub2LogsGUI::class):
                break;
            default:
                $cmd = $this->ctrl->getCmd(self::CMD_INDEX);
                $this->{$cmd}();
        }
    }

    /**
     *
     */
    protected function index()/*: void*/
    {
        $this->ctrl->redirectByClass(hub2ConfigGUI::class);
    }

    /**
     *
     */
    protected function initTabs()/*: void*/
    {
        self::dic()->tabs()->addTab(
            self::TAB_PLUGIN_CONFIG,
            ilHub2Plugin::getInstance()->txt(self::TAB_PLUGIN_CONFIG),
            $this->ctrl
                ->getLinkTargetByClass(hub2ConfigGUI::class)
        );

        self::dic()->tabs()->addTab(self::TAB_ORIGINS, ilHub2Plugin::getInstance()->txt(self::TAB_ORIGINS), $this->ctrl
                                                                                                         ->getLinkTargetByClass(hub2ConfigOriginsGUI::class));

        if (ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_ACTIVE)) {
            self::dic()->tabs()->addTab(
                self::TAB_CUSTOM_VIEWS,
                ilHub2Plugin::getInstance()->txt(self::TAB_CUSTOM_VIEWS),
                $this->ctrl
                    ->getLinkTargetByClass(hub2CustomViewGUI::class)
            );
        }
    }

    /**
     *
     */
    protected function cancel()/*: void*/
    {
        $this->index();
    }

    /**
     *
     */
    protected function handleExplorerCommand()/*: void*/
    {
        (new OriginConfigFormGUI(
            new hub2ConfigOriginsGUI(),
            new OriginRepository(),
            (new OriginFactory())->getById(intval(filter_input(
                INPUT_GET,
                hub2ConfigOriginsGUI::ORIGIN_ID
            )))
        ))->getILIASFileRepositorySelector()
                                                    ->handleExplorerCommand();
    }
}
