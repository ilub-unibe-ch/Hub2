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

namespace srag\Plugins\Hub2\UI\User;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\User\IUserOriginConfig;
use srag\Plugins\Hub2\Origin\Config\User\UserOriginConfig;
use srag\Plugins\Hub2\Origin\User\ARUserOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\User\IUserProperties;
use ilHub2Plugin;

/**
 * Class UserOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARUserOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $syncfield = new ilSelectInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_config_login_field'),
            $this->conf(IUserOriginConfig::LOGIN_FIELD)
        );
        $options = [];
        foreach (UserOriginConfig::getAvailableLoginFields() as $id) {
            $options[$id] = ilHub2Plugin::getInstance()->txt('usr_config_login_field_' . $id);
        }
        $syncfield->setOptions($options);
        $syncfield->setInfo(ilHub2Plugin::getInstance()->txt('usr_config_login_field_info'));
        $syncfield->setRequired(true);
        $syncfield->setValue($this->origin->config()->getILIASLoginField());
        $this->addItem($syncfield);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();

        $activate = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_activate_account'),
            $this->prop(IUserProperties::ACTIVATE_ACCOUNT)
        );
        $activate->setChecked((bool)$this->origin->properties()->get(IUserProperties::ACTIVATE_ACCOUNT));
        $this->addItem($activate);
        //
        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_create_password'),
            $this->prop(IUserProperties::CREATE_PASSWORD)
        );
        $cb->setChecked((bool)$this->origin->properties()->get(IUserProperties::CREATE_PASSWORD));
        $this->addItem($cb);
        $send_password = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_send_password'),
            $this->prop(IUserProperties::SEND_PASSWORD)
        );
        $send_password->setChecked((bool)$this->origin->properties()->get(IUserProperties::SEND_PASSWORD));
        //		$syncfield = new ilSelectInputGUI(ilHub2Plugin::getInstance()->txt('usr_prop_send_password_field'), $this->prop(UserOriginProperties::SEND_PASSWORD_FIELD));
        //		$opt = array('email'            => 'email',
        //		             'external_account' => 'external_account',
        //		             'email_password'   => 'email_password',);
        //		$syncfield->setOptions($opt);
        //		$syncfield->setValue(
        //			$this->origin->properties()
        //				->get(UserOriginProperties::SEND_PASSWORD_FIELD)
        //		);
        //		$activate->addSubItem($syncfield);

        $subject = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_password_mail_subject'),
            $this->prop(IUserProperties::PASSWORD_MAIL_SUBJECT)
        );
        $subject->setValue($this->origin->properties()->get(IUserProperties::PASSWORD_MAIL_SUBJECT));
        $send_password->addSubItem($subject);
        $mail_body = new ilTextareaInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_password_mail_body'),
            $this->prop(IUserProperties::PASSWORD_MAIL_BODY)
        );
        $mail_body->setInfo(ilHub2Plugin::getInstance()->txt('usr_prop_password_mail_placeholders') . ': [LOGIN], [PASSWORD]');
        $mail_body->setCols(80);
        $mail_body->setRows(15);
        $mail_body->setValue($this->origin->properties()->get(IUserProperties::PASSWORD_MAIL_BODY));
        $send_password->addSubItem($mail_body);
        $mail_date_format = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_password_mail_date_format'),
            $this->prop(IUserProperties::PASSWORD_MAIL_DATE_FORMAT)
        );
        $mail_date_format->setInfo('<a target=\'_blank\' href=\'http://php.net/manual/de/function.date.php\'>' . htmlspecialchars(ilHub2Plugin::getInstance()->txt('usr_prop_password_mail_date_format_info')) . '</a>');
        $mail_date_format->setValue($this->origin->properties()->get(IUserProperties::PASSWORD_MAIL_DATE_FORMAT));
        $send_password->addSubItem($mail_date_format);
        $this->addItem($send_password);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $activate = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_reactivate_account'),
            $this->prop(IUserProperties::REACTIVATE_ACCOUNT)
        );
        $activate->setInfo(ilHub2Plugin::getInstance()->txt('usr_prop_reactivate_account_info'));
        $activate->setChecked((bool)$this->origin->properties()->get(IUserProperties::REACTIVATE_ACCOUNT));
        $this->addItem($activate);

        $activate = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_resend_password'),
            $this->prop(IUserProperties::RE_SEND_PASSWORD)
        );
        $activate->setInfo(ilHub2Plugin::getInstance()->txt('usr_prop_resend_password_info'));
        $activate->setChecked((bool)$this->origin->properties()->get(IUserProperties::RE_SEND_PASSWORD));
        $this->addItem($activate);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('usr_prop_delete_mode'),
            $this->prop(IUserProperties::DELETE)
        );
        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('usr_prop_delete_mode_none'),
            (string) IUserProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('usr_prop_delete_mode_inactive'),
            (string) IUserProperties::DELETE_MODE_INACTIVE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('usr_prop_delete_mode_delete'),
            (string) IUserProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);
        $delete->setValue((string)$this->origin->properties()->get(IUserProperties::DELETE));
        $this->addItem($delete);
    }
}
