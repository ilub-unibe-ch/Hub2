<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

use srag\Plugins\Hub2\Object\IMetadataAwareObject;
use srag\Plugins\Hub2\Object\ITaxonomyAwareObject;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\OriginFactory;
use srag\Plugins\Hub2\UI\Data\DataTableGUI;

/**
 * Class DataGUI
 * @package srag\Plugins\Hub2\UI\Data
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class hub2DataGUI extends hub2MainGUI
{
    /**
     *
     */
    public function executeCommand()
    {
        $this->initTabs();
        $cmd = $this->ctrl->getCmd(self::CMD_INDEX);
        $this->{$cmd}();
    }

    /**
     *
     */
    protected function index()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $this->ui->mainTemplate()->setContent($table->getHTML());
    }

    /**
     *
     */
    protected function applyFilter()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $table->writeFilterToSession();
        $table->resetOffset();
        //$this->ctrl->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function resetFilter()
    {
        $table = new DataTableGUI($this, self::CMD_INDEX);
        $table->resetFilter();
        $table->resetOffset();
        //$this->ctrl->redirect($this, self::CMD_INDEX);
        $this->index(); // Fix reset offset
    }

    /**
     *
     */
    protected function initTabs()
    {
        $this->tabs->activateSubTab(hub2ConfigOriginsGUI::SUBTAB_DATA);
    }

    /**
     *
     */
    protected function renderData()
    {
        $ext_id = $this->request->getQueryParams()[DataTableGUI::F_EXT_ID];
        $origin_id = $this->request->getQueryParams()[DataTableGUI::F_ORIGIN_ID];

        $origin_factory = new OriginFactory();
        $object_factory = new ObjectFactory($origin_factory->getById($origin_id));

        $object = $object_factory->undefined($ext_id);

        $factory = $this->ui->factory();

        /*$properties = array_merge([
            "period" => $object->getPeriod(),
            "delivery_date" => $object->getDeliveryDate()->format(DATE_ATOM),
            "processed_date" => $object->getProcessedDate()->format(DATE_ATOM),
            "ilias_id" => $object->getILIASId(),
            "status" => $object->getStatus(),
        ], $object->getData());*/
        $properties = $object->getData(); // Only dto properties

        if ($object instanceof IMetadataAwareObject) {
            foreach ($object->getMetaData() as $metadata) {
                $properties["metadata." . $metadata->getIdentifier()] = $metadata->getValue();
            }
        }

        if ($object instanceof ITaxonomyAwareObject) {
            foreach ($object->getTaxonomies() as $taxonomy) {
                $properties["taxonomy." . $taxonomy->getTitle()] = $taxonomy->getNodeTitlesAsArray();
            }
        }

        $filtered = [];
        foreach ($properties as $key => $property) {
            if (!is_null($property)) {
                if (is_array($property)) {
                    $filtered[$key] = implode(',', $property);
                } else {
                    $filtered[$key] = (string) $property;
                }
            }
            if ($property === '') {
                $filtered[$key] = "&nbsp;";
            }
        }

        ksort($filtered);

        // Unfortunately the item suchs in rendering in Modals, therefore we take a descriptive listing
        /*$data_table = $factory->item()->standard(ilHub2Plugin::getInstance()->txt("data_table_ext_id", "", [ $object->getExtId() ]))
            ->withProperties($filtered);*/

        $data_table = $factory->listing()->descriptive($filtered);

        $modal = $factory->modal()->roundtrip(
            ilHub2Plugin::getInstance()->txt("data_table_header_data") . "<br>" . ilHub2Plugin::getInstance()->txt("data_table_hash"),
            $data_table
        )->withCancelButtonLabel("close");

        $this->ui->mainTemplate()->setContent($this->ui->renderer()->renderAsync($modal));
    }
}
