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

namespace srag\Plugins\Hub2\Sync\Processor\Course;

use srag\Plugins\Hub2\Sync\Processor\IMetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ITaxonomySyncProcessor;

/**
 * Interface ICourseSyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\Course
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseSyncProcessor extends IObjectSyncProcessor, IMetadataSyncProcessor, ITaxonomySyncProcessor
{

}
