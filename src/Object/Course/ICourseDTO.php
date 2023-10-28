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

namespace srag\Plugins\Hub2\Object\Course;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAndMetadataAwareDataTransferObject;

/**
 * Interface ICourseDTO
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ICourseDTO extends IDataTransferObject, ITaxonomyAndMetadataAwareDataTransferObject, IMappingStrategyAwareDataTransferObject
{
    // @see ilCourseConstants
    public const SUBSCRIPTION_TYPE_DEACTIVATED = 1;
    public const SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP = 2;
    public const SUBSCRIPTION_TYPE_DIRECTLY = 3;
    public const SUBSCRIPTION_TYPE_PASSWORD = 4;
    public const VIEW_MODE_SESSIONS = 0; //\ilContainer::VIEW_SESSIONS;
    public const VIEW_MODE_OBJECTIVES = 1; //\ilContainer::VIEW_OBJECTIVE;
    public const VIEW_MODE_TIMING = 2; //\ilContainer::VIEW_TIMING;
    public const VIEW_MODE_SIMPLE = 4; //\ilContainer::VIEW_SIMPLE;
    public const VIEW_MODE_BY_TYPE = 5; //\ilContainer::VIEW_BY_TYPE;
    public const VIEW_MODE_INHERIT = 6; //\ilContainer::VIEW_INHERIT;
    public const PARENT_ID_TYPE_REF_ID = 1;
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    public const ACTIVATION_OFFLINE = 0;
    public const ACTIVATION_UNLIMITED = 1;
    public const ACTIVATION_LIMITED = 2;
    public const VIEW_DEFAULT = self::VIEW_MODE_BY_TYPE;
    public const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
    public const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
    public const SORT_ACTIVATION = 2;//\ilContainer::SORT_ACTIVATION;
    public const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
    public const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
    public const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
    public const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
}
