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

namespace srag\Plugins\Hub2\UI\Config;

use hub2ConfigGUI;
use ilCheckboxInputGUI;
use ilFormPropertyGUI;
use ilFormSectionHeaderGUI;
use ilHub2ConfigGUI;
use ilHub2Plugin;
use ilNumberInputGUI;
use ilPropertyFormGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;

use srag\Plugins\Hub2\Config\ArConfig;


/**
 * Class ConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Config
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ConfigFormGUI extends ilPropertyFormGUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var ilHub2ConfigGUI
     */
    protected $parent_gui;

    /**
     * ConfigFormGUI constructor
     * @param hub2ConfigGUI $parent_gui
     */
    public function __construct(hub2ConfigGUI $parent_gui)
    {
        parent::__construct();

        $this->parent_gui = $parent_gui;

        $this->initForm();
    }

    /**
     *
     */
    protected function initForm(): void
    {
        $this->setFormAction($this->ctrl->getFormAction($this->parent_gui));

        $this->addCommandButton(hub2ConfigGUI::CMD_SAVE_CONFIG, ilHub2Plugin::getInstance()->txt('button_save'));
        $this->addCommandButton(hub2ConfigGUI::CMD_CANCEL, ilHub2Plugin::getInstance()->txt('button_cancel'));

        $this->setTitle(ilHub2Plugin::getInstance()->txt('admin_form_title'));

        $item = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_origins_path'),
            ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH
        );
        $item->setInfo(ilHub2Plugin::getInstance()->txt('admin_origins_path_info'));
        $item->setValue(ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH));
        $this->addItem($item);

        $cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('admin_lock'), ArConfig::KEY_LOCK_ORIGINS_CONFIG);
        $cb->setChecked(ArConfig::getField(ArConfig::KEY_LOCK_ORIGINS_CONFIG));
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_GLOBAL_HOCK_ACTIVE),
            ArConfig::KEY_GLOBAL_HOCK_ACTIVE
        );
        $cb->setChecked(ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_ACTIVE));
        $sub_item = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_GLOBAL_HOCK_PATH),
            ArConfig::KEY_GLOBAL_HOCK_PATH
        );
        $sub_item->setValue(ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_PATH));
        $sub_item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_GLOBAL_HOCK_PATH . '_info'));
        $cb->addSubItem($sub_item);
        $sub_item = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_GLOBAL_HOCK_CLASS),
            ArConfig::KEY_GLOBAL_HOCK_CLASS
        );
        $sub_item->setValue(ArConfig::getField(ArConfig::KEY_GLOBAL_HOCK_CLASS));
        $sub_item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_GLOBAL_HOCK_CLASS . '_info'));
        $cb->addSubItem($sub_item);
        $this->addItem($cb);

        $item = new ilFormSectionHeaderGUI();
        $item->setTitle(ilHub2Plugin::getInstance()->txt("logs"));
        $this->addItem($item);

        $item = new ilNumberInputGUI(
            ilHub2Plugin::getInstance()->txt(ArConfig::KEY_KEEP_OLD_LOGS_TIME),
            ArConfig::KEY_KEEP_OLD_LOGS_TIME
        );
        $item->setSuffix(ilHub2Plugin::getInstance()->txt("days"));
        $item->setInfo(ilHub2Plugin::getInstance()->txt(ArConfig::KEY_KEEP_OLD_LOGS_TIME . "_info"));
        $item->setMinValue(0);
        $item->setValue((string)ArConfig::getField(ArConfig::KEY_KEEP_OLD_LOGS_TIME));
        $this->addItem($item);

        $item = new ilFormSectionHeaderGUI();
        $item->setTitle(ilHub2Plugin::getInstance()->txt('common_permissions'));
        $this->addItem($item);

        $item = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('common_roles'), ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS);
        $item->setValue(implode(
            ', ',
            ArConfig::getField(ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS)
        )); // TODO: Use better config gui for getAdministrationRoleIds
        $item->setInfo(ilHub2Plugin::getInstance()->txt('admin_roles_info'));
        $this->addItem($item);

        $h = new ilFormSectionHeaderGUI();
        $h->setTitle(ilHub2Plugin::getInstance()->txt('admin_shortlink'));
        $this->addItem($h);

        $item = new ilTextAreaInputGUI(ilHub2Plugin::getInstance()->txt('admin_msg_'
            . ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND), ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND);
        $item->setUseRte(false);
        $item->setValue(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND));
        $item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_SHORTLINK_OBJECT_NOT_FOUND . '_info'));
        $this->addItem($item);

        $item = new ilTextAreaInputGUI(ilHub2Plugin::getInstance()->txt('admin_msg_'
            . ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE), ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE);
        $item->setUseRte(false);
        $item->setValue(ArConfig::getField(ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE));
        $item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_SHORTLINK_OBJECT_NOT_ACCESSIBLE . '_info'));
        $this->addItem($item);

        $item = new ilTextAreaInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_SHORTLINK_SUCCESS),
            ArConfig::KEY_SHORTLINK_SUCCESS
        );
        $item->setUseRte(false);
        $item->setValue(ArConfig::getField(ArConfig::KEY_SHORTLINK_SUCCESS));
        $item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_SHORTLINK_SUCCESS . '_info'));
        $this->addItem($item);

        $h = new ilFormSectionHeaderGUI();
        $h->setTitle(ilHub2Plugin::getInstance()->txt('views'));
        $this->addItem($h);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_CUSTOM_VIEWS_ACTIVE),
            ArConfig::KEY_CUSTOM_VIEWS_ACTIVE
        );
        $cb->setChecked(ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_ACTIVE));
        $sub_item = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_CUSTOM_VIEWS_PATH),
            ArConfig::KEY_CUSTOM_VIEWS_PATH
        );
        $sub_item->setValue(ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_PATH));
        $sub_item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_CUSTOM_VIEWS_PATH . '_info'));
        $cb->addSubItem($sub_item);
        $sub_item = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_CUSTOM_VIEWS_CLASS),
            ArConfig::KEY_CUSTOM_VIEWS_CLASS
        );
        $sub_item->setValue(ArConfig::getField(ArConfig::KEY_CUSTOM_VIEWS_CLASS));
        $sub_item->setInfo(ilHub2Plugin::getInstance()->txt('admin_msg_' . ArConfig::KEY_CUSTOM_VIEWS_CLASS . '_info'));
        $cb->addSubItem($sub_item);
        $this->addItem($cb);

        //		$h = new ilFormSectionHeaderGUI();
        //		$h->setTitle(ilHub2Plugin::getInstance()->txt('admin_membership'));
        //		$this->addItem($h);
        //
        //		$cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('admin_membership_activate'), ArConfig::KEY_MMAIL_ACTIVE);
        //		$cb->setInfo(ilHub2Plugin::getInstance()->txt('admin_membership_activate_info'));
        //		$this->addItem($cb);
        //
        //		$mm = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_membership_mail_subject'), ArConfig::KEY_MMAIL_SUBJECT);
        //		$mm->setInfo(ilHub2Plugin::getInstance()->txt('admin_membership_mail_subject_info'));
        //		$this->addItem($mm);
        //
        //		$mm = new ilTextAreaInputGUI(ilHub2Plugin::getInstance()->txt('admin_membership_mail_msg'), ArConfig::KEY_MMAIL_MSG);
        //		$mm->setInfo(nl2br(ilHub2Plugin::getInstance()->txt('admin_membership_mail_msg_info'), false));
        //		$this->addItem($mm);
        //
        //		$h = new ilFormSectionHeaderGUI();
        //		$h->setTitle(ilHub2Plugin::getInstance()->txt('admin_user_creation'));
        //		$this->addItem($h);
        //
        //		$ti = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_user_creation_standard_role'), ArConfig::KEY_STANDARD_ROLE);
        //		$this->addItem($ti);
        //
        //		$h = new ilFormSectionHeaderGUI();
        //		$h->setTitle(ilHub2Plugin::getInstance()->txt('admin_header_sync'));
        //		$this->addItem($h);
        //
        //		$cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('admin_use_async'), ArConfig::KEY_USE_ASYNC);
        //		$cb->setInfo(ilHub2Plugin::getInstance()->txt('admin_use_async_info'));
        //
        //		$te = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_async_user'), ArConfig::KEY_ASYNC_USER);
        //		$cb->addSubItem($te);
        //		$te = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_async_password'), ArConfig::KEY_ASYNC_PASSWORD);
        //		$cb->addSubItem($te);
        //		$te = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_async_client'), ArConfig::KEY_ASYNC_CLIENT);
        //		$cb->addSubItem($te);
        //		$te = new ilTextInputGUI(ilHub2Plugin::getInstance()->txt('admin_async_cli_php'), ArConfig::KEY_ASYNC_CLI_PHP);
        //		$cb->addSubItem($te);
        //		$this->addItem($cb);

        //		$cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('admin_import_export'), ArConfig::KEY_IMPORT_EXPORT);
        //		$this->addItem($cb);
    }

    /**
     *
     */
    public function updateConfig()/*: void*/
    {
        foreach ($this->getInputItemsRecursive() as $item) {
            /** @var ilFormPropertyGUI $item */
            switch ($item->getPostVar()) {
                case ArConfig::KEY_ADMINISTRATE_HUB_ROLE_IDS:
                    $administration_role_ids = $this->getInput($item->getPostVar());
                    $administration_role_ids = preg_split('/, */', $administration_role_ids);
                    $administration_role_ids = array_map(function (string $id): int {
                        return intval($id);
                    }, $administration_role_ids);
                    ArConfig::setField(
                        $item->getPostVar(),
                        $administration_role_ids
                    ); // TODO: Use better config gui for getAdministrationRoleIds
                    break;
                default:
                    ArConfig::setField($item->getPostVar(), $this->getInput($item->getPostVar()));
                    break;
            }
        }
    }
}
