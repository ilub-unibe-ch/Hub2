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

namespace srag\Plugins\Hub2\Origin\Config\Session;

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;

/**
 * Interface ISessionOriginConfig
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @package srag\Plugins\Hub2\Origin\Config\Session
 */
interface ISessionOriginConfig extends IOriginConfig
{

    //	const REF_ID_NO_PARENT_ID_FOUND = 'ref_id_no_parent_id_found';
    //	const EXT_ID_NO_PARENT_ID_FOUND = 'ext_id_no_parent_id_found';
    //
    //
    //	/**
    //	 * Get the ILIAS ref-ID acting as parent, only if hub was not able to find
    //	 * the correct parent ref-ID. By default, the category will be created directly
    //	 * in the repository (refId = 1).
    //	 *
    //	 * @return int
    //	 */
    //	public function getParentRefIdIfNoParentIdFound();
    //
    //
    //	/**
    //	 * Get an external ID of another category from the same origin acting as parent,
    //	 * only if hub was not able to find the "correct" parent category given by the parent id. If
    //	 * there is no "fallback category" found with the ext-ID returned here, the category
    //	 * will be created directly in the repository (refId = 1)
    //	 *
    //	 * @return mixed
    //	 */
    //	public function getExternalParentIdIfNoParentIdFound();
}
