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

namespace srag\Plugins\Hub2\UI\Group;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\Course\ICourseOriginConfig;
use srag\Plugins\Hub2\Origin\Group\ARGroupOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\Group\IGroupProperties;
use ilHub2Plugin;

/**
 * Class GroupOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARGroupOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()->txt('grp_prop_node_noparent'),
            $this->conf(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo(ilHub2Plugin::getInstance()->txt('grp_prop_node_noparent_info'));
        $te->setValue($this->origin->properties()->get(ICourseOriginConfig::REF_ID_NO_PARENT_ID_FOUND));
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('grp_prop_move'),
            $this->prop(IGroupProperties::MOVE_GROUP)
        );
        $cb->setChecked((bool)$this->origin->properties()->get(IGroupProperties::MOVE_GROUP));
        $cb->setInfo(ilHub2Plugin::getInstance()->txt('grp_prop_move_info'));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode'),
            $this->prop(IGroupProperties::DELETE_MODE)
        );
        $delete->setValue((string)$this->origin->properties()->get(IGroupProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_none'),
            (string) IGroupProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_close'),
            (string) IGroupProperties::DELETE_MODE_CLOSED
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_delete'),
            (string) IGroupProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_delete_or_close'),
            (string) IGroupProperties::DELETE_MODE_DELETE_OR_CLOSE
        );
        $opt->setInfo(ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_delete_or_close_info'));
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('grp_prop_delete_mode_trash'),
            (string) IGroupProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
