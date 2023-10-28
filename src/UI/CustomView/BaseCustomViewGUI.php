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

namespace srag\Plugins\Hub2\UI\CustomView;

use hub2CustomViewGUI;
use ilHub2Plugin;
/**
 * Class BaseCustomViewGUI
 * @package srag\Plugins\Hub2\UI\CustomView
 * @author  Timon Amstutz
 */
abstract class BaseCustomViewGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var hub2CustomViewGUI
     */
    protected hub2CustomViewGUI $parent_gui;

    /**
     * BaseCustomViewGUI constructor
     * @param hub2CustomViewGUI $parent_gui
     */
    public function __construct(hub2CustomViewGUI $parent_gui)
    {
        $this->parent_gui = $parent_gui;
    }

    /**
     *
     */
    abstract public function executeCommand()/*: void*/
    ;
}
