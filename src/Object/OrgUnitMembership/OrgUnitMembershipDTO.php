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

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class OrgUnitMembershipDTO
 * @package srag\Plugins\Hub2\Object\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitMembershipDTO extends DataTransferObject implements IOrgUnitMembershipDTO
{
    /**
     * @var int|string
     */
    protected $org_unit_id;
    /**
     * @var int
     */
    protected int $org_unit_id_type = self::ORG_UNIT_ID_TYPE_OBJ_ID;
    /**
     * @var int
     */
    protected int $user_id;
    /**
     * @var int
     */
    protected int $position;

    /**
     * @param int|string $org_unit_id
     * @param int        $user_id
     * @param int        $position
     */
    public function __construct($org_unit_id, int $user_id, int $position)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$org_unit_id, $user_id, $position]));
        $this->org_unit_id = $org_unit_id;
        $this->user_id = $user_id;
        $this->position = $position;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitId()
    {
        return $this->org_unit_id;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitId($org_unit_id): IOrgUnitMembershipDTO
    {
        $this->org_unit_id = $org_unit_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitIdType(): int
    {
        return $this->org_unit_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitIdType(int $org_unit_id_type): IOrgUnitMembershipDTO
    {
        $this->org_unit_id_type = $org_unit_id_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @inheritdoc
     */
    public function setUserId(int $user_id): IOrgUnitMembershipDTO
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @inheritdoc
     */
    public function setPosition(int $position): IOrgUnitMembershipDTO
    {
        $this->position = $position;

        return $this;
    }
}
