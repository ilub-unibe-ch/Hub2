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

namespace srag\Plugins\Hub2\UI\OriginConfig;

use hub2ConfigOriginsGUI;
use ilAdvancedSelectionListGUI;
use ilHub2Plugin;
use ilTable2GUI;

use srag\DIC\Hub2\Exception\DICException;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Origin\IOriginRepository;


/**
 * Class OriginsTableGUI
 * @package srag\Plugins\Hub2\UI\OriginConfig
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginsTableGUI extends ilTable2GUI
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    protected $a_parent_obj;
    /**
     * @var IOriginRepository
     */
    protected IOriginRepository $originRepository;

    /**
     * @param hub2ConfigOriginsGUI $a_parent_obj
     * @param string               $a_parent_cmd
     * @param IOriginRepository    $originRepository
     * @throws DICException
     * @internal param
     */
    public function __construct($a_parent_obj, $a_parent_cmd, IOriginRepository $originRepository)
    {
        $this->originRepository = $originRepository;
        $this->a_parent_obj = $a_parent_obj;
        $this->setPrefix('hub2_');
        $this->setId('origins');
        $this->setTitle(ilHub2Plugin::getInstance()->txt('hub_origins'));
        parent::__construct($a_parent_obj, $a_parent_cmd);
        $this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
        $this->setRowTemplate('tpl.std_row_template.html', 'Services/ActiveRecord');
        $this->initColumns();
        $this->initTableData();
        $this->addCommandButton(
            hub2ConfigOriginsGUI::CMD_DEACTIVATE_ALL,
            ilHub2Plugin::getInstance()->txt('origin_table_button_deactivate_all')
        );
        $this->addCommandButton(
            hub2ConfigOriginsGUI::CMD_ACTIVATE_ALL,
            ilHub2Plugin::getInstance()->txt('origin_table_button_activate_all')
        );
    }

    /**
     *
     */
    protected function initColumns()
    {
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_id'), 'id');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_sort'), 'sort');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_active'), 'active');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_title'), 'title');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_description'), 'description');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_usage_type'), 'object_type');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('origin_table_header_last_update'), 'last_sync');
        $this->addColumn(ilHub2Plugin::getInstance()->txt('common_actions'));
    }

    /**
     *
     */
    protected function initTableData()
    {
        $data = [];
        foreach ($this->originRepository->all() as $origin) {
            $class = "srag\\Plugins\\Hub2\\Object\\" . ucfirst($origin->getObjectType()) . "\\" . ucfirst($origin->getObjectType()) . "Repository";
            /** @var IObjectRepository $objectRepository */
            $objectRepository = new $class($origin);
            $row = [];
            $row['id'] = $origin->getId();
            $row['sort'] = $origin->getSort();
            $row['active'] = ilHub2Plugin::getInstance()->txt("common_" . ($origin->isActive() ? "yes" : "no"));
            $row['title'] = $origin->getTitle();
            $row['description'] = $origin->getDescription();
            $row['object_type'] = ilHub2Plugin::getInstance()->txt("origin_object_type_" . $origin->getObjectType());
            $row['last_sync'] = $origin->getLastRun();
            $data[] = $row;
        }
        $this->setData($data);
        $this->setDefaultOrderField("sort");
        $this->setDefaultOrderDirection("asc");
    }

    /**
     * @param array $a_set
     */
    protected function fillRow(array $a_set)
    {
        foreach ($a_set as $key => $value) {
            $this->tpl->setCurrentBlock('cell');
            $this->tpl->setVariable('VALUE', !is_null($value) ? $value : "&nbsp;");
            $this->tpl->parseCurrentBlock();
        }
        $actions = new ilAdvancedSelectionListGUI();
        $actions->setId('actions_' . $a_set['id']);
        $actions->setListTitle(ilHub2Plugin::getInstance()->txt('common_actions'));
        $this->ctrl->setParameter($this->parent_obj, 'origin_id', $a_set['id']);
        $actions->addItem(ilHub2Plugin::getInstance()->txt('common_edit'), 'edit', $this->ctrl
                                                                                ->getLinkTarget(
                                                                                    $this->parent_obj,
                                                                                    hub2ConfigOriginsGUI::CMD_EDIT_ORGIN
                                                                                ));
        $actions->addItem(ilHub2Plugin::getInstance()->txt('common_delete'), 'delete', $this->ctrl
                                                                                    ->getLinkTarget(
                                                                                        $this->parent_obj,
                                                                                        hub2ConfigOriginsGUI::CMD_CONFIRM_DELETE
                                                                                    ));
        $actions->addItem(ilHub2Plugin::getInstance()->txt('origin_table_button_run'), 'runOriginSync', $this->ctrl
                                                                                                     ->getLinkTarget(
                                                                                                         $this->parent_obj,
                                                                                                         hub2ConfigOriginsGUI::CMD_RUN_ORIGIN_SYNC
                                                                                                     ));
        $actions->addItem(
            ilHub2Plugin::getInstance()->txt('origin_table_button_run_force_update'),
            'runOriginSyncForceUpdate',
            $this->ctrl
                ->getLinkTarget($this->parent_obj, hub2ConfigOriginsGUI::CMD_RUN_ORIGIN_SYNC_FORCE_UPDATE)
        );
        $this->ctrl->clearParameters($this->parent_obj);
        $this->tpl->setCurrentBlock('cell');
        $this->tpl->setVariable('VALUE', self::output()->getHTML($actions));
        $this->tpl->parseCurrentBlock();
    }
}
