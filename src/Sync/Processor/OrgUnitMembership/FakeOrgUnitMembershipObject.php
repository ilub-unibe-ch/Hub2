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

namespace srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership;

use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class FakeOrgUnitMembershipObject
 * @package srag\Plugins\Hub2\Sync\Processor\OrgUnitMembership
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FakeOrgUnitMembershipObject extends FakeIliasMembershipObject
{
    /**
     * @var int
     */
    protected int $position_id;

    /**
     * @param int $container_id_ilias
     * @param int $user_id_ilias
     * @param int $position_id
     */
    public function __construct(int $container_id_ilias, int $user_id_ilias, int $position_id)
    {
        parent::__construct($container_id_ilias, $user_id_ilias);

        $this->position_id = $position_id;

        $this->initId();
    }

    /**
     * @return int
     */
    public function getPositionId(): int
    {
        return $this->position_id;
    }

    /**
     * @param int $position_id
     */
    public function setPositionId(int $position_id)
    {
        $this->position_id = $position_id;
    }

    /**
     *
     */
    public function initId()
    {
        $this->setId(implode(self::GLUE, [$this->container_id_ilias, $this->user_id_ilias, $this->position_id]));
    }

    /**
     * @param string $id
     * @return FakeOrgUnitMembershipObject
     */
    public static function loadInstanceWithConcatenatedId(string $id): FakeOrgUnitMembershipObject
    {
        list($container_id_ilias, $user_id_ilias, $position_id) = explode(self::GLUE, $id);

        return new self((int) $container_id_ilias, (int) $user_id_ilias, (int) $position_id);
    }
}
