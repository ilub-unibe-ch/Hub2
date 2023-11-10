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

namespace srag\Plugins\Hub2\Object\User;

use DateTime;
use InvalidArgumentException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\MetadataAwareDataTransferObject;
use srag\Plugins\Hub2\Config\Config;

/**
 * Class UserDTO
 * @package srag\Plugins\Hub2\Object\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserDTO extends DataTransferObject implements IUserDTO
{
    use MetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;

    /**
     * @var array
     */
    private static array $genders = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
        self::GENDER_NONE
    ];
    /**
     * @var array
     */
    private static array $auth_modes = [
        self::AUTH_MODE_ILIAS,
        self::AUTH_MODE_SHIB,
        self::AUTH_MODE_LDAP,
        self::AUTH_MODE_RADIUS,
    ];

    protected string $authMode = self::AUTH_MODE_ILIAS;
    protected ?string $externalAccount = null;
    protected ?string $passwd = null;
    protected ?string $firstname = null;
    protected ?string $lastname = null;
    protected ?string $login = null;
    protected ?string $title = null;
    protected ?string $gender = null;
    protected ?string $email = null;
    protected ?string $emailPassword = null;
    protected ?string $institution = null;
    protected ?string $street = null;
    protected ?string $city = null;
    protected ?int $zipcode = null;
    protected ?string $country = null;
    protected ?string $selectedCountry = null;
    protected ?string $phoneOffice = null;
    protected ?string $department = null;
    protected ?string $phoneHome = null;
    protected ?string $phoneMobile = null;
    protected ?string $fax = null;
    protected ?int $timeLimitOwner = null;
    protected bool $timeLimitUnlimited = true;
    protected ?string $timeLimitFrom = null;
    protected ?string $timeLimitUntil = null;
    protected ?string $matriculation = null;
    protected ?string $birthday = null;
    /**
     * @var array
     * @description usr_prop_ilias_roles_info
     */
    protected array $iliasRoles = [];

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): UserDTO
    {
        $this->passwd = $passwd;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): UserDTO
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): UserDTO
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): UserDTO
    {
        $this->login = $login;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): UserDTO
    {
        $this->title = $title;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): UserDTO
    {
        if (!in_array($gender, self::$genders)) {
            throw new InvalidArgumentException("'$gender' is not a valid gender");
        }
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserDTO
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailPassword(): ?string
    {
        return $this->emailPassword;
    }

    public function setEmailPassword(string $emailPassword): UserDTO
    {
        $this->emailPassword = $emailPassword;

        return $this;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): UserDTO
    {
        $this->institution = $institution;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): UserDTO
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): UserDTO
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): UserDTO
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): UserDTO
    {
        $this->country = $country;

        return $this;
    }

    public function getSelectedCountry(): ?string
    {
        return $this->selectedCountry;
    }

    public function setSelectedCountry(string $selectedCountry): UserDTO
    {
        $this->selectedCountry = $selectedCountry;

        return $this;
    }

    public function getPhoneOffice(): ?string
    {
        return $this->phoneOffice;
    }

    public function setPhoneOffice(string $phoneOffice): UserDTO
    {
        $this->phoneOffice = $phoneOffice;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): UserDTO
    {
        $this->department = $department;

        return $this;
    }

    public function getPhoneHome(): ?string
    {
        return $this->phoneHome;
    }

    public function setPhoneHome(string $phoneHome): UserDTO
    {
        $this->phoneHome = $phoneHome;

        return $this;
    }

    public function getPhoneMobile(): ?string
    {
        return $this->phoneMobile;
    }

    public function setPhoneMobile(string $phoneMobile): UserDTO
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): UserDTO
    {
        $this->fax = $fax;

        return $this;
    }

    public function getTimeLimitOwner(): ?int
    {
        return $this->timeLimitOwner;
    }

    public function setTimeLimitOwner(int $timeLimitOwner): UserDTO
    {
        $this->timeLimitOwner = $timeLimitOwner;

        return $this;
    }

    public function getTimeLimitUnlimited(): bool
    {
        return $this->timeLimitUnlimited;
    }

    public function setTimeLimitUnlimited(bool $timeLimitUnlimited): UserDTO
    {
        $this->timeLimitUnlimited = $timeLimitUnlimited;

        return $this;
    }

    public function getTimeLimitFrom(): ?string
    {
        return $this->timeLimitFrom;
    }

    public function setTimeLimitFrom(DateTime $timeLimitFrom): UserDTO
    {
        $this->timeLimitFrom = $timeLimitFrom->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    public function getTimeLimitUntil(): ?string
    {
        return $this->timeLimitUntil;
    }

    public function setTimeLimitUntil(DateTime $timeLimitUntil): UserDTO
    {
        $this->timeLimitUntil = $timeLimitUntil->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    public function getMatriculation(): ?string
    {
        return $this->matriculation;
    }

    public function setMatriculation(string $matriculation): UserDTO
    {
        $this->matriculation = $matriculation;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(DateTime $birthday): UserDTO
    {
        $this->birthday = $birthday->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    public function getIliasRoles(): array
    {
        return $this->iliasRoles;
    }

    public function setIliasRoles(array $iliasRoles): UserDTO
    {
        $this->iliasRoles = $iliasRoles;

        return $this;
    }

    public function getAuthMode(): ?string
    {
        return $this->authMode;
    }

    public function setAuthMode(string $authMode): UserDTO
    {
        if (!in_array($authMode, self::$auth_modes)) {
            throw new InvalidArgumentException("'$authMode' is not a valid account type");
        }
        $this->authMode = $authMode;

        return $this;
    }

    public function getExternalAccount(): ?string
    {
        return $this->externalAccount;
    }

    public function setExternalAccount(string $externalAccount): UserDTO
    {
        $this->externalAccount = $externalAccount;

        return $this;
    }

    public function __toString()
    {
        return implode(', ', [
            "ext_id: " . $this->getExtId(),
            "period: " . $this->getPeriod(),
            "firstname: " . $this->getFirstname(),
            "lastname: " . $this->getLastname(),
            "email: " . $this->getEmail(),
        ]);
    }
}
