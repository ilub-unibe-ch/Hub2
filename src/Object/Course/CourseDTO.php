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

namespace srag\Plugins\Hub2\Object\Course;

use InvalidArgumentException;
use srag\Plugins\Hub2\Exception\LanguageCodeException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAndMetadataAwareDataTransferObject;

/**
 * Class CourseDTO
 * @package srag\Plugins\Hub2\Object\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseDTO extends DataTransferObject implements ICourseDTO
{
    use TaxonomyAndMetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;

    /**
     * @var array
     */
    private static array $subscriptionTypes = [
        self::SUBSCRIPTION_TYPE_DEACTIVATED,
        self::SUBSCRIPTION_TYPE_REQUEST_MEMBERSHIP,
        self::SUBSCRIPTION_TYPE_DIRECTLY,
        self::SUBSCRIPTION_TYPE_PASSWORD,
    ];
    /**
     * @var array
     */
    private static array $viewModes = [
        self::VIEW_MODE_SESSIONS,
        self::VIEW_MODE_OBJECTIVES,
        self::VIEW_MODE_TIMING,
        self::VIEW_MODE_SIMPLE,
        self::VIEW_MODE_BY_TYPE,
        self::VIEW_MODE_INHERIT
    ];
    /**
     * Copied from ilMDLanguageItem::_getPossibleLanguageCodes
     * @var string[]
     */
    private static array $available_languages = [
        "aa",
        "ab",
        "af",
        "am",
        "ar",
        "as",
        "ay",
        "az",
        "ba",
        "be",
        "bg",
        "bh",
        "bi",
        "bn",
        "bo",
        "br",
        "ca",
        "co",
        "cs",
        "cy",
        "da",
        "de",
        "dz",
        "el",
        "en",
        "eo",
        "es",
        "et",
        "eu",
        "fa",
        "fi",
        "fj",
        "fo",
        "fr",
        "fy",
        "ga",
        "gd",
        "gl",
        "gn",
        "gu",
        "ha",
        "he",
        "hi",
        "hr",
        "hu",
        "hy",
        "ia",
        "ie",
        "ik",
        "id",
        "is",
        "it",
        "iu",
        "ja",
        "jv",
        "ka",
        "kk",
        "kl",
        "km",
        "kn",
        "ko",
        "ks",
        "ku",
        "ky",
        "la",
        "ln",
        "lo",
        "lt",
        "lv",
        "mg",
        "mi",
        "mk",
        "ml",
        "mn",
        "mo",
        "mr",
        "ms",
        "mt",
        "my",
        "na",
        "ne",
        "nl",
        "no",
        "oc",
        "om",
        "or",
        "pa",
        "pl",
        "ps",
        "pt",
        "qu",
        "rm",
        "rn",
        "ro",
        "ru",
        "rw",
        "sa",
        "sd",
        "sg",
        "sh",
        "si",
        "sk",
        "sl",
        "sm",
        "sn",
        "so",
        "sq",
        "sr",
        "ss",
        "st",
        "su",
        "sv",
        "sw",
        "ta",
        "te",
        "tg",
        "th",
        "ti",
        "tk",
        "tl",
        "tn",
        "to",
        "tr",
        "ts",
        "tt",
        "tw",
        "ug",
        "uk",
        "ur",
        "uz",
        "vi",
        "vo",
        "wo",
        "xh",
        "yi",
        "yo",
        "za",
        "zh",
        "zu"
    ];
    /**
     * @var array
     */
    private static array $parentIdTypes = [
        self::PARENT_ID_TYPE_REF_ID,
        self::PARENT_ID_TYPE_EXTERNAL_EXT_ID,
    ];
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
    protected string $importantInformation;
    /**
     * @var string
     */
    protected string $contactResponsibility;
    /**
     * @var string
     */
    protected string $contactEmail;
    /**
     * @var int
     */
    protected int $parentId;
    /**
     * @var int
     */
    protected int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var string
     */
    protected string $firstDependenceCategory;
    /**
     * @var string
     */
    protected string $secondDependenceCategory;
    /**
     * @var string
     */
    protected string $thirdDependenceCategory;
    /**
     * @var string
     */
    protected string $fourthDependenceCategory;
    /**
     * @var int
     */
    protected int $template_id = 0;
    /**
     * @var array
     */
    protected array $notificationEmails = [];
    /**
     * @var int
     */
    protected int $owner = 6;
    /**
     * @var int
     */
    protected int $subscriptionLimitationType = 0;
    /**
     * @var int
     */
    protected int $viewMode = self::VIEW_MODE_SESSIONS;
    /**
     * @var string
     */
    protected string $syllabus = '';
    /**
     * @var string
     */
    protected string $contactName;
    /**
     * @var string
     */
    protected string $contactConsultation;
    /**
     * @var string
     */
    protected string $contactPhone;
    /**
     * @var int
     */
    protected int $activationType = self::ACTIVATION_OFFLINE;
    /**
     * @var string
     */
    protected string $languageCode = 'en';
    /**
     * @var int
     */
    protected int $didacticTemplate;
    /**
     * @var string
     */
    protected string $icon;
    /**
     * @var bool
     */
    protected bool $sessionLimitEnabled = false;
    /**
     * @var int
     */
    protected int $numberOfPreviousSessions = -1;
    /**
     * @var int
     */
    protected int $numberOfNextSessions = -1;
    /**
     * @var int
     */
    protected int $orderType = self::SORT_TITLE;
    /**
     * @var int
     */
    protected int $orderDirection = self::SORT_DIRECTION_ASC;
    /**
     * @var string
     */
    protected string $appointementsColor = '';

    /**
     * @var bool
     */
    protected bool $showMembers = true;

    /**
     * @var bool
     */
    protected bool $showMembersExport = false;

    /**
     * @var bool
     */
    protected bool $newsSetting = true;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CourseDTO
     */
    public function setTitle(string $title): CourseDTO
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
     * @return CourseDTO
     */
    public function setDescription(string $description): CourseDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getImportantInformation(): string
    {
        return $this->importantInformation;
    }

    /**
     * @param string $importantInformation
     * @return CourseDTO
     */
    public function setImportantInformation(string $importantInformation): CourseDTO
    {
        $this->importantInformation = $importantInformation;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactResponsibility(): string
    {
        return $this->contactResponsibility;
    }

    /**
     * @param string $contactResponsibility
     * @return CourseDTO
     */
    public function setContactResponsibility(string $contactResponsibility): CourseDTO
    {
        $this->contactResponsibility = $contactResponsibility;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     * @return CourseDTO
     */
    public function setContactEmail(string $contactEmail): CourseDTO
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstDependenceCategory(): string
    {
        return $this->firstDependenceCategory;
    }

    /**
     * @param string $firstDependenceCategory
     * @return CourseDTO
     */
    public function setFirstDependenceCategory(string $firstDependenceCategory): CourseDTO
    {
        $this->firstDependenceCategory = $firstDependenceCategory;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondDependenceCategory(): string
    {
        return $this->secondDependenceCategory;
    }

    /**
     * @param string $secondDependenceCategory
     * @return CourseDTO
     */
    public function setSecondDependenceCategory(string $secondDependenceCategory): CourseDTO
    {
        $this->secondDependenceCategory = $secondDependenceCategory;

        return $this;
    }

    /**
     * @return string
     */
    public function getThirdDependenceCategory(): string
    {
        return $this->thirdDependenceCategory;
    }

    /**
     * @param string $thirdDependenceCategory
     * @return CourseDTO
     */
    public function setThirdDependenceCategory(string $thirdDependenceCategory): CourseDTO
    {
        $this->thirdDependenceCategory = $thirdDependenceCategory;

        return $this;
    }

    /**
     * @return string
     */
    public function getFourthDependenceCategory(): string
    {
        return $this->fourthDependenceCategory;
    }

    /**
     * @param string $fourthDependenceCategory
     * @return CourseDTO
     */
    public function setFourthDependenceCategory(string $fourthDependenceCategory): CourseDTO
    {
        $this->fourthDependenceCategory = $fourthDependenceCategory;

        return $this;
    }

    /**
     * @return int
     */
    public function getTemplateId(): int
    {
        return $this->template_id;
    }

    /**
     * @param int $template_id
     * @return $this
     */
    public function setTemplateId(int $template_id): CourseDTO
    {
        $this->template_id = $template_id;

        return $this;
    }

    /**
     * @return array
     */
    public function getNotificationEmails(): array
    {
        return $this->notificationEmails;
    }

    /**
     * @param array $notificationEmails
     * @return CourseDTO
     */
    public function setNotificationEmails(array $notificationEmails): CourseDTO
    {
        $this->notificationEmails = $notificationEmails;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return CourseDTO
     */
    public function setOwner(int $owner): CourseDTO
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubscriptionLimitationType(): int
    {
        return $this->subscriptionLimitationType;
    }

    /**
     * @param int $subscriptionLimitationType
     * @return CourseDTO
     */
    public function setSubscriptionLimitationType(int $subscriptionLimitationType): CourseDTO
    {
        if (!in_array($subscriptionLimitationType, self::$subscriptionTypes)) {
            throw new InvalidArgumentException("Given $subscriptionLimitationType does not exist");
        }
        $this->subscriptionLimitationType = $subscriptionLimitationType;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewMode(): int
    {
        return $this->viewMode;
    }

    /**
     * @param int $viewMode
     * @return CourseDTO
     */
    public function setViewMode(int $viewMode): CourseDTO
    {
        if (!in_array($viewMode, self::$viewModes)) {
            throw new InvalidArgumentException("Given $viewMode does not exist");
        }
        $this->viewMode = $viewMode;

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
     * @param int $parentId
     * @return $this
     */
    public function setParentId(int $parentId): CourseDTO
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
     * @return CourseDTO
     */
    public function setParentIdType(int $parentIdType): CourseDTO
    {
        if (!in_array($parentIdType, self::$parentIdTypes)) {
            throw new InvalidArgumentException("Invalid parentIdType given '$parentIdType'");
        }
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSyllabus(): string
    {
        return $this->syllabus;
    }

    /**
     * @param string $syllabus
     * @return CourseDTO
     */
    public function setSyllabus(string $syllabus): CourseDTO
    {
        $this->syllabus = $syllabus;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName(): string
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     * @return CourseDTO
     */
    public function setContactName(string $contactName): CourseDTO
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactConsultation(): string
    {
        return $this->contactConsultation;
    }

    /**
     * @param string $contactConsultation
     * @return CourseDTO
     */
    public function setContactConsultation(string $contactConsultation): CourseDTO
    {
        $this->contactConsultation = $contactConsultation;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     * @return CourseDTO
     */
    public function setContactPhone(string $contactPhone): CourseDTO
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * @return int
     */
    public function getActivationType(): int
    {
        return $this->activationType;
    }

    /**
     * @param int $activationType
     * @return CourseDTO
     */
    public function setActivationType(int $activationType): CourseDTO
    {
        $this->activationType = $activationType;

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
     * @param string $languageCode
     * @return CourseDTO
     * @throws LanguageCodeException
     */
    public function setLanguageCode(string $languageCode): CourseDTO
    {
        if (!self::isLanguageCode($languageCode)) {
            throw new LanguageCodeException($languageCode);
        }

        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getDidacticTemplate(): int
    {
        return $this->didacticTemplate;
    }

    /**
     * @param int $didacticTemplate
     * @return CourseDTO
     */
    public function setDidacticTemplate(int $didacticTemplate): CourseDTO
    {
        $this->didacticTemplate = $didacticTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return is_string($this->icon) ? $this->icon : '';
    }

    /**
     * @param string $icon
     * @return CourseDTO
     */
    public function setIcon(string $icon): CourseDTO
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string $languageCode
     * @return bool
     */
    public static function isLanguageCode(string $languageCode): bool
    {
        return in_array($languageCode, self::$available_languages);
    }

    /**
     * @return bool
     */
    public function isSessionLimitEnabled(): bool
    {
        return $this->sessionLimitEnabled;
    }

    /**
     * @param bool $sessionLimitEnabled
     */
    public function enableSessionLimit(bool $sessionLimitEnabled)
    {
        $this->sessionLimitEnabled = $sessionLimitEnabled;
    }

    /**
     * @return int
     */
    public function getNumberOfPreviousSessions(): int
    {
        return $this->numberOfPreviousSessions;
    }

    /**
     * @param int $numberOfPreviousSessions
     */
    public function setNumberOfPreviousSessions(int $numberOfPreviousSessions)
    {
        $this->numberOfPreviousSessions = $numberOfPreviousSessions;
    }

    /**
     * @return int
     */
    public function getNumberOfNextSessions(): int
    {
        return $this->numberOfNextSessions;
    }

    /**
     * @param int $numberOfNextSessions
     */
    public function setNumberOfNextSessions(int $numberOfNextSessions)
    {
        $this->numberOfNextSessions = $numberOfNextSessions;
    }

    /**
     * @return int
     */
    public function getOrderType(): int
    {
        return $this->orderType;
    }

    /**
     * @param int $orderType
     * @return $this
     */
    public function setOrderType(int $orderType): CourseDTO
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderDirection(): int
    {
        return $this->orderDirection;
    }

    /**
     * @param int $orderDirection
     * @return $this
     */
    public function setOrderDirection(int $orderDirection): CourseDTO
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppointementsColor(): string
    {
        return $this->appointementsColor;
    }

    /**
     * @param string $appointementsColor
     */
    public function setAppointementsColor(string $appointementsColor): CourseDTO
    {
        $this->appointementsColor = $appointementsColor;

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
    public function setShowMembers(bool $showMembers): CourseDTO
    {
        $this->showMembers = $showMembers;
        return $this;
    }

    /**
     * @return bool
     */
    public function getShowMembersExport(): bool
    {
        return $this->showMembersExport;
    }

    /**
     * @param bool $showMembersExport
     * @return $this
     */
    public function setShowMembersExport(bool $showMembersExport): CourseDTO
    {
        $this->showMembersExport = $showMembersExport;
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
    public function setNewsSetting(bool $newsSetting): CourseDTO
    {
        $this->newsSetting = $newsSetting;
        return $this;
    }
}
