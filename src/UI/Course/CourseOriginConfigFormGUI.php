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

namespace srag\Plugins\Hub2\UI\Course;

use ilCheckboxInputGUI;
use ilEMailInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\Course\ICourseOriginConfig;
use srag\Plugins\Hub2\Origin\Course\ARCourseOrigin;
use srag\Plugins\Hub2\Origin\Properties\Course\CourseProperties;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\Course\ICourseProperties;
use ilHub2Plugin;

/**
 * Class CourseOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCourseOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        // Extend shortlink
        //		$shortlink = $this->getItemByPostVar($this->prop(IOriginConfig::LINKED_ORIGIN_ID));
        //		$cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('crs_prop_check_online'), hubCourseFields::F_SL_CHECK_ONLINE);
        //		$msg = new ilTextAreaInputGUI(ilHub2Plugin::getInstance()->txt('crs_prop_' . hubCourseFields::F_MSG_NOT_ONLINE), hubCourseFields::F_MSG_NOT_ONLINE);
        //		$msg->setRows(2);
        //		$msg->setCols(100);
        //		$cb->addSubItem($msg);
        //		$shortlink->addSubItem($cb);
        //

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_node_noparent'),
            $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo(ilHub2Plugin::getInstance()->txt('crs_prop_node_noparent_info'));
        $te->setValue($this->origin->properties()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_activate'),
            $this->prop(ICourseProperties::SET_ONLINE)
        );
        $cb->setChecked((bool)$this->origin->properties()->get(ICourseProperties::SET_ONLINE));
        $this->addItem($cb);

        //		$cb = new ilCheckboxInputGUI(ilHub2Plugin::getInstance()->txt('crs_prop_create_icon'), $this->prop(CourseOriginProperties::CREATE_ICON));
        //		$this->addItem($cb);

        $send_mail = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_send_notification'),
            $this->prop(ICourseProperties::SEND_CREATE_NOTIFICATION)
        );
        $send_mail->setInfo(ilHub2Plugin::getInstance()->txt('crs_prop_send_notification_info'));
        $send_mail->setChecked((bool)$this->origin->properties()->get(ICourseProperties::SEND_CREATE_NOTIFICATION));
        $notification_subject = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_notification_subject'),
            $this->prop(ICourseProperties::CREATE_NOTIFICATION_SUBJECT)
        );
        $notification_subject->setValue($this->origin->properties()->get(ICourseProperties::CREATE_NOTIFICATION_SUBJECT));

        $send_mail->addSubItem($notification_subject);
        $notification_body = new ilTextAreaInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_notification_body'),
            $this->prop(ICourseProperties::CREATE_NOTIFICATION_BODY)
        );
        $notification_body->setInfo(CourseProperties::getPlaceHolderStrings());
        $notification_body->setRows(6);
        $notification_body->setCols(100);
        $notification_body->setValue($this->origin->properties()->get(ICourseProperties::CREATE_NOTIFICATION_BODY));
        $send_mail->addSubItem($notification_body);
        $notification_from = new ilEMailInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_notification_from'),
            $this->prop(ICourseProperties::CREATE_NOTIFICATION_FROM)
        );
        $notification_from->setValue($this->origin->properties()->get(ICourseProperties::CREATE_NOTIFICATION_FROM));
        $send_mail->addSubItem($notification_from);
        $this->addItem($send_mail);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_move'),
            $this->prop(ICourseProperties::MOVE_COURSE)
        );
        $cb->setChecked((bool)$this->origin->properties()->get(ICourseProperties::MOVE_COURSE));

        $cb->setInfo(ilHub2Plugin::getInstance()->txt('crs_prop_move_info'));
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_reactivate'),
            $this->prop(ICourseProperties::SET_ONLINE_AGAIN)
        );
        $cb->setChecked((bool)$this->origin->properties()->get(ICourseProperties::SET_ONLINE_AGAIN));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode'),
            $this->prop(ICourseProperties::DELETE_MODE)
        );
        $delete->setValue((string)$this->origin->properties()->get(ICourseProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_none'),
            (string) ICourseProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_inactive'),
            (string) ICourseProperties::DELETE_MODE_OFFLINE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_delete'),
            (string) ICourseProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_delete_or_inactive'),
            (string) ICourseProperties::DELETE_MODE_DELETE_OR_OFFLINE
        );
        $opt->setInfo(nl2br(ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_delete_or_inactive_info'), false));
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_trash'),
            (string) ICourseProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
