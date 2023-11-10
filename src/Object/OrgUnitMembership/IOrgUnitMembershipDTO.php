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

namespace srag\Plugins\Hub2\Object\OrgUnitMembership;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;

/**
 * Interface IOrgUnitMembershipDTO
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface IOrgUnitMembershipDTO extends IDataTransferObject
{
    /**
     * @var int
     */
    public const ORG_UNIT_ID_TYPE_OBJ_ID = 1;
    /**
     * @var int
     */
    public const ORG_UNIT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var int
     */
    public const POSITION_EMPLOYEE = 1;
    /**
     * @var int
     */
    public const POSITION_SUPERIOR = 2;

    /**
     * @return int|string
     */
    public function getOrgUnitId();

    /**
     * @param int|string $org_unit_id
     * @return IOrgUnitMembershipDTO
     */
    public function setOrgUnitId($org_unit_id): IOrgUnitMembershipDTO;

    /**
     * @return int
     */
    public function getOrgUnitIdType(): int;

    /**
     * @param int $org_unit_id_type
     * @return IOrgUnitMembershipDTO
     */
    public function setOrgUnitIdType(int $org_unit_id_type): IOrgUnitMembershipDTO;

    /**
     * @return int
     */
    public function getUserId(): int;

    /**
     * @param int $user_id
     * @return IOrgUnitMembershipDTO
     */
    public function setUserId(int $user_id): IOrgUnitMembershipDTO;

    /**
     * @return int
     */
    public function getPosition(): int;

    /**
     * @param int $position
     * @return IOrgUnitMembershipDTO
     */
    public function setPosition(int $position): IOrgUnitMembershipDTO;
}
