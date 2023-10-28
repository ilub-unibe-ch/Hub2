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

namespace srag\Plugins\Hub2\Origin\Properties\Category;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICategoryProperties extends IOriginProperties
{
    public const SHOW_INFO_TAB = 'show_info_tab';
    public const SHOW_NEWS = 'show_news';
    public const DELETE_MODE = 'delete_mode';
    public const MOVE_CATEGORY = 'move_category';
    public const DELETE_MODE_MARK_TEXT = 'delete_mode_mark_text';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_MARK = 1;
    public const DELETE_MODE_DELETE = 2;

    /**
     * @return array
     */
    public static function getAvailableDeleteModes(): array;
}
