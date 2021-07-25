<?php

namespace srag\Plugins\Hub2\Object\Group;

use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IMetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\Course\CourseDTO;

/**
 * Class GroupDTO
 *
 * @package srag\Plugins\Hub2\Object\Group
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class GroupDTO extends DataTransferObject implements IMetadataAwareDataTransferObject, ITaxonomyAwareDataTransferObject {

	use MetadataAwareDataTransferObject;
	use TaxonomyAwareDataTransferObject;
	// View
    const VIEW_SESSIONS = 0;
	const VIEW_SIMPLE = 4;
	const VIEW_BY_TYPE = 5;
	const VIEW_INHERIT = 6;
	// Registration
	const GRP_REGISTRATION_DEACTIVATED = - 1;
	const GRP_REGISTRATION_DIRECT = 0;
	const GRP_REGISTRATION_REQUEST = 1;
	const GRP_REGISTRATION_PASSWORD = 2;

	const GRP_REGISTRATION_LIMITED = 1;
	const GRP_REGISTRATION_UNLIMITED = 2;

	// Type
	const GRP_TYPE_UNKNOWN = 0;
	const GRP_TYPE_CLOSED = 1;
	const GRP_TYPE_OPEN = 2;
	const GRP_TYPE_PUBLIC = 3;
	// Sortation
	const SORT_TITLE = 0;//\ilContainer::SORT_TITLE;
	const SORT_MANUAL = 1;//\ilContainer::SORT_MANUAL;
	const SORT_INHERIT = 3;//\ilContainer::SORT_INHERIT;
	const SORT_CREATION = 4;//\ilContainer::SORT_CREATION;
	const SORT_DIRECTION_ASC = 0;//\ilContainer::SORT_DIRECTION_ASC;
	const SORT_DIRECTION_DESC = 1;//\ilContainer::SORT_DIRECTION_DESC;
	// Other
	const MAIL_ALLOWED_ALL = 1;
	const MAIL_ALLOWED_TUTORS = 2;
	const PARENT_ID_TYPE_REF_ID = 1;
	const PARENT_ID_TYPE_EXTERNAL_EXT_ID = 2;
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var string
	 */
	protected $information;
	/**
	 * @var int
	 */
	protected $groupType;
	/**
	 * @var int
	 */
	protected $registrationType;
	/**
	 * @var bool
	 */
	protected $regUnlimited;
	/**
	 * @var int timestamp
	 */
	protected $registrationStart;
	/**
	 * @var int timestamp
	 */
	protected $registrationEnd;
	/**
	 * @var int
	 */
	protected $owner;
	/**
	 * @var string
	 */
	protected $password;
	/**
	 * @var bool
	 */
	protected $regMembershipLimitation;
	/**
	 * @var int
	 */
	protected $minMembers;
	/**
	 * @var int
	 */
	protected $maxMembers;
	/**
	 * @var bool
	 */
	protected $waitingList;
	/**
	 * @var bool
	 */
	protected $waitingListAutoFill;
	/**
	 * @var int timestamp
	 */
	protected $cancellationEnd;
	/**
	 * @var int timestamp
	 */
	protected $start;
	/**
	 * @var int timestamp
	 */
	protected $end;
	/**
	 * @var float
	 */
	protected $latitude;
	/**
	 * @var  float
	 */
	protected $longitude;
	/**
	 * @var int
	 */
	protected $locationzoom;
	/**
	 * @var int
	 */
	protected $enableGroupMap;
	/**
	 * @var bool
	 */
	protected $regAccessCodeEnabled;
	/**
	 * @var string
	 */
	protected $registrationAccessCode;
	/**
	 * @var int
	 */
	protected $viewMode;
    /**
     * @var bool
     */
    protected $sessionLimit;
    /**
     * @var int
     */
    protected $numberOfNextSessions;
    /**
     * @var int
     */
    protected $numberOfPreviousSessions;
	/**
	 * @var string
	 */
	protected $parentId;
	/**
	 * @var int
	 */
	protected $parentIdType = self::PARENT_ID_TYPE_REF_ID;
	/**
	 * @var string
	 */
	protected $appointementsColor = '';

    /**
     * @var string
     */
    protected $languageCode = 'en';
	/**
	 * @var int
	 */
	protected $orderType = self::SORT_TITLE;
	/**
	 * @var int
	 */
	protected $orderDirection = self::SORT_DIRECTION_ASC;

    /**
     * @var bool
     */
    protected $newsSetting=true;

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 *
	 * @return GroupDTO
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 *
	 * @return GroupDTO
	 */
	public function setDescription($description) {
		$this->description = $description;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegistrationType() {
		return $this->registrationType;
	}


	/**
	 * @param int $registrationType
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationType($registrationType) {
		$this->registrationType = $registrationType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getInformation() {
		return $this->information;
	}


	/**
	 * @param string $information
	 *
	 * @return GroupDTO
	 */
	public function setInformation($information) {
		$this->information = $information;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getGroupType() {
		return $this->groupType;
	}


	/**
	 * @param int $groupType
	 *
	 * @return GroupDTO
	 */
	public function setGroupType($groupType) {
		$this->groupType = $groupType;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getOwner() {
		return $this->owner;
	}


	/**
	 * @param int $owner
	 *
	 * @return GroupDTO
	 */
	public function setOwner($owner) {
		$this->owner = $owner;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegUnlimited() {
		return $this->regUnlimited;
	}


	/**
	 * @param bool $regUnlimited
	 *
	 * @return GroupDTO
	 */
	public function setRegUnlimited($regUnlimited) {
		$this->regUnlimited = $regUnlimited;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegistrationStart() {
		return $this->registrationStart;
	}


	/**
	 * @param int $registrationStart
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationStart($registrationStart) {
		$this->registrationStart = $registrationStart;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getRegistrationEnd() {
		return $this->registrationEnd;
	}


	/**
	 * @param int $registrationEnd
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationEnd($registrationEnd) {
		$this->registrationEnd = $registrationEnd;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}


	/**
	 * @param string $password
	 *
	 * @return GroupDTO
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegMembershipLimitation() {
		return $this->regMembershipLimitation;
	}


	/**
	 * @param bool $regMembershipLimitation
	 *
	 * @return GroupDTO
	 */
	public function setRegMembershipLimitation($regMembershipLimitation) {
		$this->regMembershipLimitation = $regMembershipLimitation;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getMinMembers() {
		return $this->minMembers;
	}


	/**
	 * @param int $minMembers
	 *
	 * @return GroupDTO
	 */
	public function setMinMembers($minMembers) {
		$this->minMembers = $minMembers;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getMaxMembers() {
		return $this->maxMembers;
	}


	/**
	 * @param int $maxMembers
	 *
	 * @return GroupDTO
	 */
	public function setMaxMembers($maxMembers) {
		$this->maxMembers = $maxMembers;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getWaitingList() {
		return $this->waitingList;
	}


	/**
	 * @param bool $waitingList
	 *
	 * @return GroupDTO
	 */
	public function setWaitingList($waitingList) {
		$this->waitingList = $waitingList;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getWaitingListAutoFill() {
		return $this->waitingListAutoFill;
	}


	/**
	 * @param bool $waitingListAutoFill
	 *
	 * @return GroupDTO
	 */
	public function setWaitingListAutoFill($waitingListAutoFill) {
		$this->waitingListAutoFill = $waitingListAutoFill;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getCancellationEnd() {
		return $this->cancellationEnd;
	}


	/**
	 * @param int $cancellationEnd
	 *
	 * @return GroupDTO
	 */
	public function setCancellationEnd($cancellationEnd) {
		$this->cancellationEnd = $cancellationEnd;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getStart() {
		return $this->start;
	}


	/**
	 * @param int $start
	 *
	 * @return GroupDTO
	 */
	public function setStart($start) {
		$this->start = $start;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getEnd() {
		return $this->end;
	}


	/**
	 * @param int $end
	 *
	 * @return GroupDTO
	 */
	public function setEnd($end) {
		$this->end = $end;

		return $this;
	}


	/**
	 * @return float
	 */
	public function getLatitude() {
		return $this->latitude;
	}


	/**
	 * @param float $latitude
	 *
	 * @return GroupDTO
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;

		return $this;
	}


	/**
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}


	/**
	 * @param float $longitude
	 *
	 * @return GroupDTO
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getLocationzoom() {
		return $this->locationzoom;
	}


	/**
	 * @param int $locationzoom
	 *
	 * @return GroupDTO
	 */
	public function setLocationzoom($locationzoom) {
		$this->locationzoom = $locationzoom;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getEnableGroupMap() {
		return $this->enableGroupMap;
	}


	/**
	 * @param int $enableGroupMap
	 *
	 * @return GroupDTO
	 */
	public function setEnableGroupMap($enableGroupMap) {
		$this->enableGroupMap = $enableGroupMap;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getRegAccessCodeEnabled() {
		return $this->regAccessCodeEnabled;
	}


	/**
	 * @param bool $regAccessCodeEnabled
	 *
	 * @return GroupDTO
	 */
	public function setRegAccessCodeEnabled($regAccessCodeEnabled) {
		$this->regAccessCodeEnabled = $regAccessCodeEnabled;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getRegistrationAccessCode() {
		return $this->registrationAccessCode;
	}


	/**
	 * @param string $registrationAccessCode
	 *
	 * @return GroupDTO
	 */
	public function setRegistrationAccessCode($registrationAccessCode) {
		$this->registrationAccessCode = $registrationAccessCode;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getViewMode() {
		return $this->viewMode;
	}


	/**
	 * @param int $viewMode
	 *
	 * @return GroupDTO
	 */
	public function setViewMode($viewMode) {
		$this->viewMode = $viewMode;

		return $this;
	}

    public function setSessionLimit($bool)
    {
        $this->sessionLimit = $bool;
        return $this;
    }

    public function getSessionLimit()
    {
        return $this->sessionLimit;

    }

    public function setNumberOfPreviousSessions($number)
    {
        $this->numberOfPreviousSessions = $number;
        return $this;
    }

    public function setNumberOfNextSessions($number)
    {
        $this->numberOfNextSessions = $number;
        return $this;
    }

    public function getNumberOfPreviousSessions()
    {
        return $this->numberOfPreviousSessions;

    }

    public function getNumberOfNextSessions()
    {
        return $this->numberOfNextSessions;

    }



	/**
	 * @return string
	 */
	public function getParentId() {
		return $this->parentId;
	}


	/**
	 * @param string $parentId
	 *
	 * @return GroupDTO
	 */
	public function setParentId($parentId) {
		$this->parentId = $parentId;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getParentIdType() {
		return $this->parentIdType;
	}


	/**
	 * @param int $parentIdType
	 *
	 * @return GroupDTO
	 */
	public function setParentIdType($parentIdType) {
		$this->parentIdType = $parentIdType;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getAppointementsColor(): string {
		return $this->appointementsColor;
	}


	/**
	 * @param string $appointementsColor
	 */
	public function setAppointementsColor(string $appointementsColor) {
		$this->appointementsColor = $appointementsColor;

		return $this;
	}

    /**
     * @return string
     */
    public function getLanguageCode() {
        return $this->languageCode;
    }


    /**
     * @param $languageCode
     * @return GroupDTO
     * @throws LanguageCodeException
     */
    public function setLanguageCode($languageCode): GroupDTO {
        if (!CourseDTO::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }

        $this->languageCode = $languageCode;

        return $this;
    }

	/**
	 * @return int
	 */
	public function getOrderType(): int {
		return $this->orderType;
	}


	/**
	 * @param int $orderType
	 * @return $this
	 */
	public function setOrderType(int $orderType) {
		$this->orderType = $orderType;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getOrderDirection(): int {
		return $this->orderDirection;
	}


	/**
	 * @param int $orderDirection
	 * @return $this
	 */
	public function setOrderDirection(int $orderDirection) {
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
    public function setNewsSetting(bool $newsSetting)
    {
        $this->newsSetting = $newsSetting;
        return $this;
    }
}