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

namespace srag\Plugins\Hub2\Shortlink\User;

use ilAdministrationGUI;
use ilLink;
use ilObjUser;
use ilObjUserGUI;
use srag\Plugins\Hub2\Shortlink\AbstractBaseLink;
use srag\Plugins\Hub2\Shortlink\IObjectLink;

/**
 * Class UserLink
 * @package srag\Plugins\Hub2\Shortlink\User
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserLink extends AbstractBaseLink implements IObjectLink
{
    /**
     * @inheritdoc
     */
    public function doesObjectExist(): bool
    {
        if (!$this->object->getILIASId()) {
            return false;
        }

        return ilObjUser::_exists((int)$this->object->getILIASId(), false);
    }

    /**
     * @inheritdoc
     */
    public function isAccessGranted(): bool
    {
        $userObj = new ilObjUser((int)$this->object->getILIASId());
        if ($userObj->hasPublicProfile()) {
            return true;
        }

        return $this->access->checkAccess('read', '', 7); // Read access to user administration
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedExternalLink(): string
    {
        return ilLink::_getLink((int)$this->object->getILIASId(), 'usr');
    }

    /**
     * @inheritdoc
     */
    public function getAccessDeniedLink(): string
    {
        return "ilias.php";
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedInternalLink(): string
    {
        $this->ctrl->setParameterByClass(ilObjUserGUI::class, "ref_id", 7);
        $this->ctrl->setParameterByClass(ilObjUserGUI::class, "obj_id", $this->object->getILIASId());

        return $this->ctrl->getLinkTargetByClass([ilAdministrationGUI::class, ilObjUserGUI::class], "view");
    }
}
