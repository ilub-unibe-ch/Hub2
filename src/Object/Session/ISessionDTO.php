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

namespace srag\Plugins\Hub2\Object\Session;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;

/**
 * Interface ISessionDTO
 * @package srag\Plugins\Hub2\Object\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ISessionDTO extends IDataTransferObject, IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject
{
    public const PARENT_ID_TYPE_REF_ID = 1;
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    public const REGISTRATION_TYPE_NONE = 0;
    public const REGISTRATION_TYPE_DIRECT = 1;
    public const REGISTRATION_TYPE_REQUEST = 3;
    public const REGISTRATION_TYPE_IMPOSSIBLE = 4;
}
