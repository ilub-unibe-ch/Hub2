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

namespace srag\Plugins\Hub2\UI\Session;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\Session\ARSessionOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\Session\ISessionProperties;

/**
 * Class SessionOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARSessionOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('sess_prop_move'),
            $this->prop(ISessionProperties::MOVE_SESSION)
        );
        $cb->setChecked($this->origin->properties()->get(ISessionProperties::MOVE_SESSION));
        $cb->setInfo(ilHub2Plugin::getInstance()->txt('sess_prop_move_info'));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('sess_prop_delete_mode'),
            $this->prop(ISessionProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(ISessionProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('sess_prop_delete_mode_none'),
            ISessionProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('sess_prop_delete_mode_delete'),
            ISessionProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('sess_prop_delete_mode_trash'),
            ISessionProperties::DELETE_MODE_MOVE_TO_TRASH
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
