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

namespace srag\Plugins\Hub2\Sync\Processor;

use srag\Plugins\Hub2\Object\IObjectRepository;

/**
 * Class FakeIliasMembershipObject
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class FakeIliasMembershipObject extends FakeIliasObject
{
    public const GLUE = IObjectRepository::GLUE;
    /**
     * @var int
     */
    protected int $user_id_ilias;
    /**
     * @var int
     */
    protected int $container_id_ilias;

    /**
     * FakeIliasMembershipObject constructor
     * @param int $container_id_ilias
     * @param int $user_id_ilias
     */
    public function __construct($container_id_ilias, int $user_id_ilias)
    {
        parent::__construct();
        $this->container_id_ilias = (int) $container_id_ilias;
        $this->user_id_ilias = $user_id_ilias;
        $this->initId();
    }

    /**
     * @param string $id
     * @return FakeIliasMembershipObject
     */
    public static function loadInstanceWithConcatenatedId(string $id): FakeIliasMembershipObject
    {
        list($container_id_ilias, $user_id_ilias) = explode(self::GLUE, $id);

        return new self((int) $container_id_ilias, (int) $user_id_ilias);
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserIdIlias(): int
    {
        return $this->user_id_ilias;
    }

    /**
     * @param int $user_id_ilias
     */
    public function setUserIdIlias(int $user_id_ilias)
    {
        $this->user_id_ilias = $user_id_ilias;
    }

    /**
     * @return int
     */
    public function getContainerIdIlias(): int
    {
        return $this->container_id_ilias;
    }

    /**
     * @param int $container_id_ilias
     */
    public function setContainerIdIlias(int $container_id_ilias)
    {
        $this->container_id_ilias = $container_id_ilias;
    }

    /**
     *
     */
    public function initId()
    {
        $this->setId(implode(self::GLUE, [$this->container_id_ilias, $this->user_id_ilias]));
    }
}
