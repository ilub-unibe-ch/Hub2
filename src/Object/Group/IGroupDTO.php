<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface IGroupDTO
 *
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IGroupDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject, IMappingStrategyAwareDataTransferObject {

	// View
	const VIEW_SIMPLE = 4;
	const VIEW_BY_TYPE = 5;
	const VIEW_INHERIT = 6;
	// Registration
	const GRP_REGISTRATION_DEACTIVATED = - 1;
	const GRP_REGISTRATION_DIRECT = 0;
	const GRP_REGISTRATION_REQUEST = 1;
	const GRP_REGISTRATION_PASSWORD = 2;
	// Type
	const GRP_REGISTRATION_LIMITED = 1;
	const GRP_REGISTRATION_UNLIMITED = 2;
	const GRP_TYPE_UNKNOWN = 0;
	const GRP_TYPE_CLOSED = 1;
	const GRP_TYPE_OPEN = 2;
	const GRP_TYPE_PUBLIC = 3;
	// Sortation
	const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
	const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
	const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
	const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
	const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
	const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
	// Other
	const MAIL_ALLOWED_ALL = 1;
	const MAIL_ALLOWED_TUTORS = 2;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
}
