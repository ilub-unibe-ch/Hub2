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

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 * @package srag\Plugins\Hub2\Origin\Properties\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupProperties extends IOriginProperties
{
    public const SET_ONLINE = 'set_online';
    public const SET_ONLINE_AGAIN = 'set_online_again';
    public const CREATE_ICON = 'create_icon';
    public const DELETE_MODE = 'delete_mode';
    public const MOVE_GROUP = 'move_group';
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_CLOSED = 1;
    public const DELETE_MODE_DELETE = 2;
    public const DELETE_MODE_DELETE_OR_CLOSE = 3;
    public const DELETE_MODE_MOVE_TO_TRASH = 4;

    /**
     * @return array
     */
    public static function getAvailableDeleteModes(): array;
}
