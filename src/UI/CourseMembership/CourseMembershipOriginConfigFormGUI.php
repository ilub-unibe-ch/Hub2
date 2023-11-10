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

namespace srag\Plugins\Hub2\UI\CourseMembership;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\Plugins\Hub2\Origin\CourseMembership\ARCourseMembershipOrigin;
use srag\Plugins\Hub2\UI\OriginConfig\OriginConfigFormGUI;
use srag\Plugins\Hub2\Origin\Properties\CourseMembership\ICourseMembershipProperties;
use ilHub2Plugin;

/**
 * Class CourseMembershipOriginConfigFormGUI
 * @package srag\Plugins\Hub2\UI\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipOriginConfigFormGUI extends OriginConfigFormGUI
{
    /**
     * @var ARCourseMembershipOrigin
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
    }

    /**
     * @inheritdoc
     */
    protected function addPropertiesDelete()
    {
        parent::addPropertiesDelete();

        $delete = new ilRadioGroupInputGUI(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode'),
            $this->prop(ICourseMembershipProperties::DELETE_MODE)
        );
        $delete->setValue((string)$this->origin->properties()->get(ICourseMembershipProperties::DELETE_MODE));

        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_prop_delete_mode_none'),
            (string) ICourseMembershipProperties::DELETE_MODE_NONE
        );
        $delete->addOption($opt);
        $opt = new ilRadioOption(
            ilHub2Plugin::getInstance()->txt('crs_membership_prop_delete_mode_delete'),
            (string) ICourseMembershipProperties::DELETE_MODE_DELETE
        );
        $delete->addOption($opt);
        $this->addItem($delete);
    }
}
