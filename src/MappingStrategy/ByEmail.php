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

namespace srag\Plugins\Hub2\MappingStrategy;

use ilObjUser;
use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;

/**
 * Class ByEmail
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ByEmail extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto): int
    {
        if (!$dto instanceof UserDTO) {
            throw new HubException("Mapping using Email not supported for this type of DTO");
        }
        $login = false;
        $user_ids_by_email = ilObjUser::_getUserIdsByEmail($dto->getEmail());
        if (is_array($user_ids_by_email)) {
            $login = $user_ids_by_email[0];
        }

        if (!$login) {
            return 0;
        }

        return (int) ilObjUser::_lookupId($login);
    }
}
