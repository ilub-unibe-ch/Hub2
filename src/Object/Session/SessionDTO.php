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

    protected string $parentId;
    protected int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    protected string $title;
    protected ?string $description = null;
    protected ?string $location = null;
    protected ?string $details = null;
    protected ?string $name = null;
    protected ?string $phone = null;
    protected ?string $email = null;
    protected ?int $registrationType = null;
    protected bool $registrationLimited = false;
    protected ?int $registrationMinUsers = null;
    protected ?int $registrationMaxUsers = null;
    protected bool $registrationWaitingList = false;
    protected ?int $cannotParticipateOption = null;
    protected bool $waitingListAutoFill = false;
    protected bool $fullDay = false;
    protected ?int $start = null;
    protected ?int $end = null;
    protected ?string $languageCode = 'en';
    protected bool $showMembers = false;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): SessionDTO
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): SessionDTO
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): SessionDTO
    {
        $this->location = $location;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): SessionDTO
    {
        $this->details = $details;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): SessionDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): SessionDTO
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): SessionDTO
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistrationType(): ?int
    {
        return $this->registrationType;
    }

    public function setRegistrationType(int $registrationType): SessionDTO
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    public function getRegistrationLimited(): bool
    {
        return $this->registrationLimited;
    }

    public function setRegistrationLimited(bool $registrationLimited): SessionDTO
    {
        $this->registrationLimited = $registrationLimited;

        return $this;
    }

    public function getRegistrationMinUsers(): ?int
    {
        return $this->registrationMinUsers;
    }

    public function setRegistrationMinUsers(int $registrationMinUsers): SessionDTO
    {
        $this->registrationMinUsers = $registrationMinUsers;

        return $this;
    }

    public function getRegistrationMaxUsers(): ?int
    {
        return $this->registrationMaxUsers;
    }

    public function setRegistrationMaxUsers(int $registrationMaxUsers): SessionDTO
    {
        $this->registrationMaxUsers = $registrationMaxUsers;

        return $this;
    }

    public function isRegistrationWaitingList(): bool
    {
        return $this->registrationWaitingList;
    }

    public function enableRegistrationWaitingList(bool $registrationWaitingList): SessionDTO
    {
        $this->registrationWaitingList = $registrationWaitingList;

        return $this;
    }

    public function getWaitingListAutoFill(): bool
    {
        return $this->waitingListAutoFill;
    }

    public function setWaitingListAutoFill(bool $waitingListAutoFill): SessionDTO
    {
        $this->waitingListAutoFill = $waitingListAutoFill;

        return $this;
    }

    public function getCannotParticipateOption(): ?int
    {
        return $this->cannotParticipateOption;
    }

    public function setCannotParticipateOption($cannotParticipateOption): SessionDTO
    {
        $this->cannotParticipateOption = $cannotParticipateOption;
        return $this;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(string $parentId): SessionDTO
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getParentIdType(): ?int
    {
        return $this->parentIdType;
    }

    public function setParentIdType(int $parentIdType): SessionDTO
    {
        $this->parentIdType = $parentIdType;

        return $this;
    }

    public function isFullDay(): bool
    {
        return $this->fullDay;
    }

    public function setFullDay(bool $fullDay): SessionDTO
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): SessionDTO
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?int
    {
        return $this->end;
    }

    public function setEnd(int $end): SessionDTO
    {
        $this->end = $end;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function setLanguageCode($languageCode): SessionDTO
    {
        if (!CourseDTO::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }

        $this->languageCode = $languageCode;

        return $this;
    }

    public function getShowMembers(): bool
    {
        return $this->showMembers;
    }

    public function setShowMembers(bool $showMembers): SessionDTO
    {
        $this->showMembers = $showMembers;
        return $this;
    }
}
