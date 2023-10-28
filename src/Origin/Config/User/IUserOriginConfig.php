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

namespace srag\Plugins\Hub2\Origin\Config\User;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface IUserOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\User
 */
interface IUserOriginConfig extends IOriginConfig
{
    //	const SYNC_FIELD_NONE = 1;
    //	const SYNC_FIELD_EMAIL = 2;
    //	const SYNC_FIELD_EXT_ID = 3;

    public const LOGIN_FIELD = 'ilias_login_field';
    public const LOGIN_FIELD_SHORTENED_FIRST_LASTNAME = 1; // John Doe => j.doe
    public const LOGIN_FIELD_EMAIL = 2;
    public const LOGIN_FIELD_EXT_ACCOUNT = 3;
    public const LOGIN_FIELD_EXT_ID = 4;
    public const LOGIN_FIELD_FIRSTNAME_LASTNAME = 5; // John Doe => john.doe
    public const LOGIN_FIELD_HUB_LOGIN = 6; // Login is picked from the login property on the UserDTO object
    /**
     * @return int
     */
    //	public function getSyncField():int;

    /**
     * @return int
     */
    public function getILIASLoginField(): int;

    /**
     * @return array
     */
    public static function getAvailableLoginFields(): array;
}
