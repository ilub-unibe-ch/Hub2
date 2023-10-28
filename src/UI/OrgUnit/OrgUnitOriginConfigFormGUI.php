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

namespace srag\Plugins\Hub2\UI\OrgUnit;

use ilTextInputGUI;
use srag\Plugins\Hub2\Origin\Config\OrgUnit\IOrgUnitOriginConfig;
use srag\Plugins\Hub2\Origin\OrgUnit\AROrgUnitOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;

/**
 * Class OrgUnitOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var AROrgUnitOrigin
     */
    protected \srag\Plugins\Hub2\Origin\IOrigin $origin;

    /**
     * @inheritdoc
     */
    protected function addSyncConfig()
    {
        parent::addSyncConfig();

        $ref_id_if_no_parent_id = new ilTextInputGUI(
            ilHub2Plugin::getInstance()
                                                         ->translate("orgunit_ref_id_if_no_parent_id"),
            $this->conf(IOrgUnitOriginConfig::REF_ID_IF_NO_PARENT_ID)
        );
        $ref_id_if_no_parent_id->setInfo(ilHub2Plugin::getInstance()->txt("orgunit_ref_id_if_no_parent_id_info"));
        $ref_id_if_no_parent_id->setValue($this->origin->config()->getRefIdIfNoParentId());
        $this->addItem($ref_id_if_no_parent_id);
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
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();
    }
}
