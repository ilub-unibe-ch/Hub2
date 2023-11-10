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

namespace srag\Plugins\Hub2\Origin\Properties\Group;

use srag\Plugins\Hub2\Origin\Properties\OriginProperties;

/**
 * Class GroupProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupProperties extends OriginProperties implements IGroupProperties
{
    /**
     * @var array
     */
    protected array $data = [
        self::SET_ONLINE => false,
        self::SET_ONLINE_AGAIN => false,
        self::CREATE_ICON => false,
        self::MOVE_GROUP => false,
        self::DELETE_MODE => self::DELETE_MODE_NONE,
    ];

    /**
     * @inheritdoc
     */
    public static function getAvailableDeleteModes(): array
    {
        return [
            self::DELETE_MODE_NONE,
            self::DELETE_MODE_CLOSED,
            self::DELETE_MODE_DELETE,
            self::DELETE_MODE_DELETE_OR_CLOSE,
            self::DELETE_MODE_MOVE_TO_TRASH,
        ];
    }
}
