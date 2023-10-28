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

use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;

/**
 * Class SessionDTO
 * @package srag\Plugins\Hub2\Object\Session
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class SessionDTO extends DataTransferObject implements ISessionDTO
{
    use MetadataAwareDataTransferObject;
    use TaxonomyAwareDataTransferObject;

    /**
     * @var string
     */
    protected string $parentId;
    /**
     * @var int
     */
    protected int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $description;
    /**
     * @var string
     */
    protected string $location;
    /**
     * @var string
     */
    protected string $details;
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var string
     */
    protected string $phone;
    /**
     * @var string
     */
    protected string $email;
    /**
     * @var int
     */
    protected int $registrationType;
    /**
     * @var bool
     */
    protected bool $registrationLimited = false;
    /**
     * @var int
     */
    protected int $registrationMinUsers;
    /**
     * @var int
     */
    protected int $registrationMaxUsers;
    /**
     * @var bool
     */
    protected bool $registrationWaitingList;
    /**
     * @var int
     */
    protected int $cannotParticipateOption;
    /**
     * @var bool
     */
    protected bool $waitingListAutoFill;
    /**
     * @var bool
     */
    protected bool $fullDay = false;
    /**
     * @var int
     */
    protected int $start;
    /**
     * @var int
     */
    protected int $end;
    /**
     * @var string
     */
    protected string $languageCode = 'en';

    /**
     * @var bool
     */
    protected bool $showMembers = false;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return SessionDTO
     */
    public function setTitle(string $title): SessionDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return SessionDTO
     */
    public function setDescription(string $description): SessionDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return SessionDTO
     */
    public function setLocation(string $location): SessionDTO
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     * @return SessionDTO
     */
    public function setDetails(string $details): SessionDTO
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SessionDTO
     */
    public function setName(string $name): SessionDTO
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return SessionDTO
     */
    public function setPhone(string $phone): SessionDTO
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return SessionDTO
     */
    public function setEmail(string $email): SessionDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationType(): int
    {
        return $this->registrationType;
    }

    /**
     * @param int $registrationType
     * @return SessionDTO
     */
    public function setRegistrationType(int $registrationType): SessionDTO
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegistrationLimited(): bool
    {
        return $this->registrationLimited;
    }

    /**
     * @param bool $registrationLimited
     * @return SessionDTO
     */
    public function setRegistrationLimited(bool $registrationLimited): SessionDTO
    {
        $this->registrationLimited = $registrationLimited;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationMinUsers(): int
    {
        return $this->registrationMinUsers;
    }

    /**
     * @param int $registrationMinUsers
     * @return SessionDTO
     */
    public function setRegistrationMinUsers(int $registrationMinUsers): SessionDTO
    {
        $this->registrationMinUsers = $registrationMinUsers;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationMaxUsers(): int
    {
        return $this->registrationMaxUsers;
    }

    /**
     * @param int $registrationMaxUsers
     * @return SessionDTO
     */
    public function setRegistrationMaxUsers(int $registrationMaxUsers): SessionDTO
    {
        $this->registrationMaxUsers = $registrationMaxUsers;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegistrationWaitingList(): bool
    {
        return $this->registrationWaitingList;
    }

    /**
     * @param bool $registrationWaitingList
     * @return SessionDTO
     */
    public function setRegistrationWaitingList(bool $registrationWaitingList): SessionDTO
    {
        $this->registrationWaitingList = $registrationWaitingList;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWaitingListAutoFill(): bool
    {
        return $this->waitingListAutoFill;
    }

    /**
     * @param bool $waitingListAutoFill
     * @return SessionDTO
     */
    public function setWaitingListAutoFill(bool $waitingListAutoFill): SessionDTO
    {
        $this->waitingListAutoFill = $waitingListAutoFill;

        return $this;
    }

    public function getCannotParticipateOption(): int
    {
        return $this->cannotParticipateOption;
    }

    public function setCannotParticipateOption($cannotParticipateOption): SessionDTO
    {
        $this->cannotParticipateOption = $cannotParticipateOption;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->parentId;
    }

    /**
     * @param string $parentId
     * @return SessionDTO
     */
    public function setParentId(string $parentId): SessionDTO
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentIdType(): int
    {
        return $this->parentIdType;
    }

    /**
     * @param int $parentIdType
     * @return SessionDTO
     */
    public function setParentIdType(int $parentIdType): SessionDTO
    {
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullDay(): bool
    {
        return $this->fullDay;
    }

    /**
     * @param bool $fullDay
     * @return SessionDTO
     */
    public function setFullDay(bool $fullDay): SessionDTO
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return SessionDTO
     */
    public function setStart(int $start): SessionDTO
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }

    /**
     * @param int $end Unix Timestamp
     * @return SessionDTO
     */
    public function setEnd(int $end): SessionDTO
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    /**
     * @param $languageCode
     * @return SessionDTO
     * @throws LanguageCodeException
     */
    public function setLanguageCode($languageCode): SessionDTO
    {
        if (!CourseDTO::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }

        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowMembers(): bool
    {
        return $this->showMembers;
    }

    /**
     * @param bool $showMembers
     * @return $this
     */
    public function setShowMembers(bool $showMembers): SessionDTO
    {
        $this->showMembers = $showMembers;
        return $this;
    }
}
