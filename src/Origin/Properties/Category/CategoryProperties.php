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

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class CategoryProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryProperties extends OriginProperties implements ICategoryProperties
{
    /**
     * @var array
     */
    protected $data = [
        self::SHOW_INFO_TAB => false,
        self::SHOW_NEWS => false,
        self::MOVE_CATEGORY => false,
        self::DELETE_MODE => self::DELETE_MODE_NONE,
        self::DELETE_MODE_MARK_TEXT => '',
    ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes(): array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_MARK,
            self::DELETE_MODE_DELETE,
        ];
    }
}
