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

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\Course\CourseDTO;
use ilDateTime;

/**
 * Class GroupDTO
 * @package srag\Plugins\Hub2\Object\Group
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupDTO extends DataTransferObject implements IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject
{
    use MetadataAwareDataTransferObject;
    use TaxonomyAwareDataTransferObject;

    // View
    public const VIEW_SESSIONS = 0;
    public const VIEW_SIMPLE = 4;
    public const VIEW_BY_TYPE = 5;
    public const VIEW_INHERIT = 6;
    // Registration
    public const GRP_REGISTRATION_DEACTIVATED = -1;
    public const GRP_REGISTRATION_DIRECT = 0;
    public const GRP_REGISTRATION_REQUEST = 1;
    public const GRP_REGISTRATION_PASSWORD = 2;

    public const GRP_REGISTRATION_LIMITED = 1;
    public const GRP_REGISTRATION_UNLIMITED = 2;

    // Type
    public const GRP_TYPE_UNKNOWN = 0;
    public const GRP_TYPE_CLOSED = 1;
    public const GRP_TYPE_OPEN = 2;
    public const GRP_TYPE_PUBLIC = 3;
    // Sortation
    public const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
    public const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
    public const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
    public const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
    public const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
    public const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
    // Other
    public const MAIL_ALLOWED_ALL = 1;
    public const MAIL_ALLOWED_TUTORS = 2;
    public const PARENT_ID_TYPE_REF_ID = 1;
    public const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
    /**
     * @var string
     */
    protected string $title;
    protected ?string $description = null;
    protected ?string $information = null;
    protected ?int $groupType = null;
    protected ?int $registrationType = null;
    protected bool $regUnlimited = false;
    protected ?ilDateTime $registrationStart = null;
    protected ?ilDateTime $registrationEnd = null;
    protected ?int $owner = null;
    protected ?string $password = null;
    protected bool $regMembershipLimitation = false;
    protected ?int $minMembers = null;
    protected ?int $maxMembers = null;
    protected bool $waitingList = false;
    protected bool $waitingListAutoFill = false;
    protected ?int $cancellationEnd = null;
    protected ?ilDateTime $start = null;
    protected ?ilDateTime $end = null;
    protected ?float $latitude = null;
    protected ?float $longitude = null;
    protected ?int $locationzoom = null;
    protected ?int $enableGroupMap = null;
    protected bool $regAccessCodeEnabled = false;
    protected ?string $registrationAccessCode = null;
    protected ?int $viewMode = null;
    protected bool $sessionLimit = false;
    protected ?int $numberOfNextSessions = null;
    protected ?int $numberOfPreviousSessions = null;
    protected string $parentId;
    protected ?int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    protected string $appointementsColor = '';
    protected string $languageCode = 'en';
    protected ?int $orderType = self::SORT_TITLE;
    protected ?int $orderDirection = self::SORT_DIRECTION_ASC;
    protected bool $newsSetting = true;

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return GroupDTO
     */
    public function setTitle(string $title): GroupDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return GroupDTO
     */
    public function setDescription(string $description): GroupDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationType(): ?int
    {
        return $this->registrationType;
    }

    /**
     * @param int $registrationType
     * @return GroupDTO
     */
    public function setRegistrationType(int $registrationType): GroupDTO
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getInformation(): ?string
    {
        return $this->information;
    }

    /**
     * @param string $information
     * @return GroupDTO
     */
    public function setInformation(string $information): GroupDTO
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroupType(): ?int
    {
        return $this->groupType;
    }

    /**
     * @param int $groupType
     * @return GroupDTO
     */
    public function setGroupType(int $groupType): GroupDTO
    {
        $this->groupType = $groupType;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner(): ?int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return GroupDTO
     */
    public function setOwner(int $owner): GroupDTO
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegUnlimited(): bool
    {
        return $this->regUnlimited;
    }

    /**
     * @param bool $regUnlimited
     * @return GroupDTO
     */
    public function setRegUnlimited(bool $regUnlimited): GroupDTO
    {
        $this->regUnlimited = $regUnlimited;

        return $this;
    }

    public function getRegistrationStart(): ?ilDateTime
    {
        return $this->registrationStart;
    }

    public function setRegistrationStart(?ilDateTime $registrationStart): GroupDTO
    {
        $this->registrationStart = $registrationStart;

        return $this;
    }

    public function getRegistrationEnd(): ?ilDateTime
    {
        return $this->registrationEnd;
    }

    public function setRegistrationEnd(?ilDateTime $registrationEnd): GroupDTO
    {
        $this->registrationEnd = $registrationEnd;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return GroupDTO
     */
    public function setPassword(string $password): GroupDTO
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegMembershipLimitation(): bool
    {
        return $this->regMembershipLimitation;
    }

    /**
     * @param bool $regMembershipLimitation
     * @return GroupDTO
     */
    public function setRegMembershipLimitation(bool $regMembershipLimitation): GroupDTO
    {
        $this->regMembershipLimitation = $regMembershipLimitation;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinMembers(): ?int
    {
        return $this->minMembers;
    }

    /**
     * @param int $minMembers
     * @return GroupDTO
     */
    public function setMinMembers(int $minMembers): GroupDTO
    {
        $this->minMembers = $minMembers;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxMembers(): ?int
    {
        return $this->maxMembers;
    }

    /**
     * @param int $maxMembers
     * @return GroupDTO
     */
    public function setMaxMembers(int $maxMembers): GroupDTO
    {
        $this->maxMembers = $maxMembers;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWaitingList(): bool
    {
        return $this->waitingList;
    }

    /**
     * @param bool $waitingList
     * @return GroupDTO
     */
    public function setWaitingList(bool $waitingList): GroupDTO
    {
        $this->waitingList = $waitingList;

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
     * @return GroupDTO
     */
    public function setWaitingListAutoFill(bool $waitingListAutoFill): GroupDTO
    {
        $this->waitingListAutoFill = $waitingListAutoFill;

        return $this;
    }

    /**
     * @return int
     */
    public function getCancellationEnd(): ?int
    {
        return $this->cancellationEnd;
    }

    /**
     * @param int $cancellationEnd
     * @return GroupDTO
     */
    public function setCancellationEnd(int $cancellationEnd): GroupDTO
    {
        $this->cancellationEnd = $cancellationEnd;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart(): ?int
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return GroupDTO
     */
    public function setStart(int $start): GroupDTO
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnd(): ?int
    {
        return $this->end;
    }

    /**
     * @param int $end
     * @return GroupDTO
     */
    public function setEnd(int $end): GroupDTO
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return GroupDTO
     */
    public function setLatitude(float $latitude): GroupDTO
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return GroupDTO
     */
    public function setLongitude(float $longitude): GroupDTO
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return int
     */
    public function getLocationzoom(): ?int
    {
        return $this->locationzoom;
    }

    /**
     * @param int $locationzoom
     * @return GroupDTO
     */
    public function setLocationzoom(int $locationzoom): GroupDTO
    {
        $this->locationzoom = $locationzoom;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnableGroupMap(): ?int
    {
        return $this->enableGroupMap;
    }

    /**
     * @param int $enableGroupMap
     * @return GroupDTO
     */
    public function setEnableGroupMap(int $enableGroupMap): GroupDTO
    {
        $this->enableGroupMap = $enableGroupMap;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegAccessCodeEnabled(): bool
    {
        return $this->regAccessCodeEnabled;
    }

    /**
     * @param bool $regAccessCodeEnabled
     * @return GroupDTO
     */
    public function setRegAccessCodeEnabled(bool $regAccessCodeEnabled): GroupDTO
    {
        $this->regAccessCodeEnabled = $regAccessCodeEnabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrationAccessCode(): ?string
    {
        return $this->registrationAccessCode;
    }

    /**
     * @param string $registrationAccessCode
     * @return GroupDTO
     */
    public function setRegistrationAccessCode(string $registrationAccessCode): GroupDTO
    {
        $this->registrationAccessCode = $registrationAccessCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewMode(): ?int
    {
        return $this->viewMode;
    }

    /**
     * @param int $viewMode
     * @return GroupDTO
     */
    public function setViewMode(int $viewMode): GroupDTO
    {
        $this->viewMode = $viewMode;

        return $this;
    }

    public function setSessionLimit($bool): GroupDTO
    {
        $this->sessionLimit = $bool;
        return $this;
    }

    public function getSessionLimit(): bool
    {
        return $this->sessionLimit;

    }

    public function setNumberOfPreviousSessions($number): GroupDTO
    {
        $this->numberOfPreviousSessions = $number;
        return $this;
    }

    public function setNumberOfNextSessions($number): GroupDTO
    {
        $this->numberOfNextSessions = $number;
        return $this;
    }

    public function getNumberOfPreviousSessions(): ?int
    {
        return $this->numberOfPreviousSessions;

    }

    public function getNumberOfNextSessions(): ?int
    {
        return $this->numberOfNextSessions;

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
     * @return GroupDTO
     */
    public function setParentId(string $parentId): GroupDTO
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
     * @return GroupDTO
     */
    public function setParentIdType(int $parentIdType): GroupDTO
    {
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppointementsColor(): ?string
    {
        return $this->appointementsColor;
    }

    /**
     * @param string $appointementsColor
     * @return GroupDTO
     */
    public function setAppointementsColor(string $appointementsColor): GroupDTO
    {
        $this->appointementsColor = $appointementsColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    /**
     * @param $languageCode
     * @return GroupDTO
     * @throws LanguageCodeException
     */
    public function setLanguageCode($languageCode): GroupDTO
    {
        if (!CourseDTO::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }

        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderType(): ?int
    {
        return $this->orderType;
    }

    /**
     * @param int $orderType
     * @return $this
     */
    public function setOrderType(int $orderType): GroupDTO
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderDirection(): ?int
    {
        return $this->orderDirection;
    }

    /**
     * @param int $orderDirection
     * @return $this
     */
    public function setOrderDirection(int $orderDirection): GroupDTO
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    /**
     * @return bool
     */
    public function getNewsSetting(): bool
    {
        return $this->newsSetting;
    }

    /**
     * @param bool
     * @return $this
     */
    public function setNewsSetting(bool $newsSetting): GroupDTO
    {
        $this->newsSetting = $newsSetting;
        return $this;
    }
}
