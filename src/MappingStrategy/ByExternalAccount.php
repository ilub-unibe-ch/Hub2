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
 * Class ByExternalAccount
 * @package srag\Plugins\Hub2\MappingStrategy
 */
class ByExternalAccount extends AMappingStrategy implements IMappingStrategy
{
    /**
     * @inheritdoc
     */
    public function map(IDataTransferObject $dto): int
    {
        if (!$dto instanceof UserDTO) {
            throw new HubException("Mapping using External Account not supported for this type of DTO");
        }
        $login = ilObjUser::_checkExternalAuthAccount($dto->getAuthMode(), $dto->getExternalAccount());

        if (!$login) {
            return 0;
        }

        return (int) ilObjUser::_lookupId($login);
    }
}
