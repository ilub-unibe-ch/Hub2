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

namespace srag\Plugins\Hub2\Object\SessionMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class SessionMembershipDTO
 * @package srag\Plugins\Hub2\Object\SessionMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionMembershipDTO extends DataTransferObject implements ISessionMembershipDTO
{
    /**
     * @var string
     */
    protected string $sessionId;
    /**
     * @var int
     */
    protected int $sessionIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var int
     */
    protected int $role;
    /**
     * @var int
     */
    protected int $userId;
    /**
     * @var bool
     */
    protected bool $isContact = false;

    /**
     * @inheritdoc
     */
    public function __construct($session_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$session_id, $user_id]));
        $this->sessionId = $session_id;
        $this->userId = $user_id;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return SessionMembershipDTO
     */
    public function setSessionId(string $sessionId): SessionMembershipDTO
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSessionIdType(): int
    {
        return $this->sessionIdType;
    }

    /**
     * @param int $sessionIdType
     * @return SessionMembershipDTO
     */
    public function setSessionIdType(int $sessionIdType): SessionMembershipDTO
    {
        $this->sessionIdType = $sessionIdType;

        return $this;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return SessionMembershipDTO
     */
    public function setRole(int $role): SessionMembershipDTO
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return SessionMembershipDTO
     */
    public function setUserId(int $userId): SessionMembershipDTO
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isContact(): bool
    {
        return $this->isContact;
    }

    /**
     * @param bool $isContact
     * @return $this
     */
    public function setIsContact(bool $isContact): SessionMembershipDTO
    {
        $this->isContact = $isContact;

        return $this;
    }
}
