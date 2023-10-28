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

namespace srag\Plugins\Hub2\UI\Category;

use ilCheckboxInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Category\ARCategoryOrigin;
use srag\Plugins\Hub2\Origin\Config\Category\ICategoryOriginConfig;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\Category\ICategoryProperties;

/**
 * Class CategoryOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCategoryOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()
                                     ->translate('cat_prop_base_node_ilias'),
            $this->conf(ICategoryOriginConfig::REF_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo(ilHub2Plugin::getInstance()->txt('cat_prop_base_node_ilias_info'));
        $te->setValue($this->origin->config()->getParentRefIdIfNoParentIdFound());
        $this->addItem($te);

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()
                                     ->translate('cat_prop_base_node_external'),
            $this->conf(ICategoryOriginConfig::EXT_ID_NO_PARENT_ID_FOUND)
        );
        $te->setInfo(ilHub2Plugin::getInstance()->txt('cat_prop_base_node_external_info'));
        $te->setValue($this->origin->config()->getExternalParentIdIfNoParentIdFound());
        $this->addItem($te);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesNew()
    {
        parent::addPropertiesNew();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('cat_prop_set_news'),
            $this->prop(ICategoryProperties::SHOW_NEWS)
        );
        $cb->setChecked($this->origin->properties()->get(ICategoryProperties::SHOW_NEWS));
        $this->addItem($cb);

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('cat_prop_set_infopage'),
            $this->prop(ICategoryProperties::SHOW_INFO_TAB)
        );
        $cb->setChecked($this->origin->properties()->get(ICategoryProperties::SHOW_INFO_TAB));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesUpdate()
    {
        parent::addPropertiesUpdate();

        $cb = new ilCheckboxInputGUI(
            ilHub2Plugin::getInstance()->txt('cat_prop_move'),
            $this->prop(ICategoryProperties::MOVE_CATEGORY)
        );
        $cb->setChecked($this->origin->properties()->get(ICategoryProperties::MOVE_CATEGORY));
        $this->addItem($cb);
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('cat_prop_delete_mode'),
            $this->prop(ICategoryProperties::DELETE_MODE)
        );
        $delete->setValue($this->origin->properties()->get(ICategoryProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('cat_prop_delete_mode_none'),
            (string) ICategoryProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);

        $opt = new ilRadioOption(ilHub2Plugin::getInstance()->txt('cat_prop_delete_mode_inactive', "", [
            ilHub2Plugin::getInstance()->txt('com_prop_mark_deleted_text')
        ]), (string) ICategoryProperties::DELETE_MODE_MARK);
        $delete->addOption($opt);

        $te = new ilTextInputGUI(
            ilHub2Plugin::getInstance()
                                     ->translate('cat_prop_delete_mode_inactive_text'),
            $this->prop(ICategoryProperties::DELETE_MODE_MARK_TEXT)
        );
        $te->setValue($this->origin->properties()->get(ICategoryProperties::DELETE_MODE_MARK_TEXT));
        $opt->addSubItem($te);

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('cat_prop_delete_mode_delete'),
            (string) ICategoryProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);

        $this->addItem($delete);
    }
}
