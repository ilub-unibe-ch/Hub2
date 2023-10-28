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

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface IGroupDTO
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject, IMappingStrategyAwareDataTransferObject
{
    // View
    public const VIEW_SIMPLE = 4;
    public const VIEW_BY_TYPE = 5;
    public const VIEW_INHERIT = 6;
    // Registration
    public const GRP_REGISTRATION_DEACTIVATED = -1;
    public const GRP_REGISTRATION_DIRECT = 0;
    public const GRP_REGISTRATION_REQUEST = 1;
    public const GRP_REGISTRATION_PASSWORD = 2;
    // Type
    public const GRP_REGISTRATION_LIMITED = 1;
    public const GRP_REGISTRATION_UNLIMITED = 2;
    public const GRP_TYPE_UNKNOWN = 0;
    public const GRP_TYPE_CLOSED = 1;
    public const GRP_TYPE_OPEN = 2;
    public const GRP_TYPE_PUBLIC = 3;
    // Sortation
    public const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
    public const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
    public const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
    public const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
    public const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
    public const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
    // Other
    public const MAIL_ALLOWED_ALL = 1;
    public const MAIL_ALLOWED_TUTORS = 2;
    public const PARENT_ID_TYPE_REF_ID = 1;
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
