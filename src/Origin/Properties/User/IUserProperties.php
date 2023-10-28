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

namespace srag\Plugins\Hub2\Origin\Properties\User;

use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface IOrgUnitMembershipOriginProperties
 * @package srag\Plugins\Hub2\Origin\Properties\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IUserProperties extends IOriginProperties
{
    public const ACTIVATE_ACCOUNT = 'activate_account';
    public const CREATE_PASSWORD = 'create_password';
    public const SEND_PASSWORD = 'send_password';
    public const RE_SEND_PASSWORD = 'resend_password';
    public const SEND_PASSWORD_FIELD = 'send_password_field';
    public const PASSWORD_MAIL_SUBJECT = 'password_mail_subject';
    public const PASSWORD_MAIL_BODY = 'password_mail_body';
    public const PASSWORD_MAIL_DATE_FORMAT = 'password_mail_date_format';
    public const REACTIVATE_ACCOUNT = 'reactivate_account';
    public const DELETE = 'delete';
    // How to handle the user if marked as TO_DELETE if data was not delivered
    // Default is "NONE" which means do nothing
    public const DELETE_MODE_NONE = 0;
    public const DELETE_MODE_DELETE = 1;
    public const DELETE_MODE_INACTIVE = 2;

    /**
     * @return array
     */
    public static function getAvailableDeleteModes(): array;
}
