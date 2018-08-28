<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use ilAdministrationGUI;
use ilLink;
use ilObjUser;
use ilObjUserGUI;

/**
 * Class UserLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class UserLink extends AbstractBaseLink implements IObjectLink {

	/**
	 * @inheritDoc
	 */
	public function doesObjectExist(): bool {
		if (!$this->object->getILIASId()) {
			return false;
		}

		return ilObjUser::_exists($this->object->getILIASId(), false);
	}


	/**
	 * @inheritDoc
	 */
	public function isAccessGranted(): bool {
		$userObj = new ilObjUser($this->object->getILIASId());
		if ($userObj->hasPublicProfile()) {
			return true;
		}

		return self::dic()->access()->checkAccess('read', '', 7); // Read access to user administration
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedExternalLink(): string {
		return ilLink::_getLink($this->object->getILIASId(), 'usr');
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessDeniedLink(): string {
		return "ilias.php";
	}


	/**
	 * @inheritDoc
	 */
	public function getAccessGrantedInternalLink(): string {
		self::dic()->ctrl()->setParameterByClass(ilObjUserGUI::class, "ref_id", 7);
		self::dic()->ctrl()->setParameterByClass(ilObjUserGUI::class, "obj_id", $this->object->getILIASId());

		return self::dic()->ctrl()->getLinkTargetByClass([ ilAdministrationGUI::class, ilObjUserGUI::class ], "view");
	}
}
