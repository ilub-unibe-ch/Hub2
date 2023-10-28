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
use srag\ActiveRecordConfig\Hub2\Config\Config;

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
    /**
     * @var string
     */
    protected string $authMode = self::AUTH_MODE_ILIAS;
    /**
     * @var string
     */
    protected string $externalAccount;
    /**
     * @var string
     */
    protected string $passwd;
    /**
     * @var string
     */
    protected string $firstname;
    /**
     * @var string
     */
    protected string $lastname;
    /**
     * @var string
     */
    protected string $login;
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $gender;
    /**
     * @var string
     */
    protected string $email;
    /**
     * @var string
     */
    protected string $emailPassword;
    /**
     * @var string
     */
    protected string $institution;
    /**
     * @var string
     */
    protected string $street;
    /**
     * @var string
     */
    protected string $city;
    /**
     * @var int
     */
    protected int $zipcode;
    /**
     * @var string
     */
    protected string $country;
    /**
     * @var string
     */
    protected string $selectedCountry;
    /**
     * @var string
     */
    protected string $phoneOffice;
    /**
     * @var string
     */
    protected string $department;
    /**
     * @var string
     */
    protected string $phoneHome;
    /**
     * @var string
     */
    protected string $phoneMobile;
    /**
     * @var string
     */
    protected string $fax;
    /**
     * @var int
     */
    protected int $timeLimitOwner;
    /**
     * @var bool
     */
    protected bool $timeLimitUnlimited = true;
    /**
     * @var string
     */
    protected string $timeLimitFrom;
    /**
     * @var string
     */
    protected string $timeLimitUntil;
    /**
     * @var string
     */
    protected string $matriculation;
    /**
     * @var string
     */
    protected string $birthday;
    /**
     * @var array
     * @description usr_prop_ilias_roles_info
     */
    protected array $iliasRoles = [];

    /**
     * @return string
     */
    public function getPasswd(): string
    {
        return $this->passwd;
    }

    /**
     * @param string $passwd
     * @return UserDTO
     */
    public function setPasswd(string $passwd): UserDTO
    {
        $this->passwd = $passwd;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return UserDTO
     */
    public function setFirstname(string $firstname): UserDTO
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return UserDTO
     */
    public function setLastname(string $lastname): UserDTO
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return UserDTO
     */
    public function setLogin(string $login): UserDTO
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return UserDTO
     */
    public function setTitle(string $title): UserDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return UserDTO
     */
    public function setGender(string $gender): UserDTO
    {
        if (!in_array($gender, self::$genders)) {
            throw new InvalidArgumentException("'$gender' is not a valid gender");
        }
        $this->gender = $gender;

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
     * @return UserDTO
     */
    public function setEmail(string $email): UserDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailPassword(): string
    {
        return $this->emailPassword;
    }

    /**
     * @param string $emailPassword
     * @return UserDTO
     */
    public function setEmailPassword(string $emailPassword): UserDTO
    {
        $this->emailPassword = $emailPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstitution(): string
    {
        return $this->institution;
    }

    /**
     * @param string $institution
     * @return UserDTO
     */
    public function setInstitution(string $institution): UserDTO
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return UserDTO
     */
    public function setStreet(string $street): UserDTO
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return UserDTO
     */
    public function setCity(string $city): UserDTO
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int
     */
    public function getZipcode(): int
    {
        return $this->zipcode;
    }

    /**
     * @param int $zipcode
     * @return UserDTO
     */
    public function setZipcode(int $zipcode): UserDTO
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return UserDTO
     */
    public function setCountry(string $country): UserDTO
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelectedCountry(): string
    {
        return $this->selectedCountry;
    }

    /**
     * @param string $selectedCountry
     * @return UserDTO
     */
    public function setSelectedCountry(string $selectedCountry): UserDTO
    {
        $this->selectedCountry = $selectedCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneOffice(): string
    {
        return $this->phoneOffice;
    }

    /**
     * @param string $phoneOffice
     * @return UserDTO
     */
    public function setPhoneOffice(string $phoneOffice): UserDTO
    {
        $this->phoneOffice = $phoneOffice;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * @param string $department
     * @return UserDTO
     */
    public function setDepartment(string $department): UserDTO
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneHome(): string
    {
        return $this->phoneHome;
    }

    /**
     * @param string $phoneHome
     * @return UserDTO
     */
    public function setPhoneHome(string $phoneHome): UserDTO
    {
        $this->phoneHome = $phoneHome;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneMobile(): string
    {
        return $this->phoneMobile;
    }

    /**
     * @param string $phoneMobile
     * @return UserDTO
     */
    public function setPhoneMobile(string $phoneMobile): UserDTO
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return UserDTO
     */
    public function setFax(string $fax): UserDTO
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeLimitOwner(): int
    {
        return $this->timeLimitOwner;
    }

    /**
     * @param int $timeLimitOwner
     * @return UserDTO
     */
    public function setTimeLimitOwner(int $timeLimitOwner): UserDTO
    {
        $this->timeLimitOwner = $timeLimitOwner;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTimeLimitUnlimited(): bool
    {
        return $this->timeLimitUnlimited;
    }

    /**
     * @param bool $timeLimitUnlimited
     * @return UserDTO
     */
    public function setTimeLimitUnlimited(bool $timeLimitUnlimited): UserDTO
    {
        $this->timeLimitUnlimited = $timeLimitUnlimited;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeLimitFrom(): string
    {
        return $this->timeLimitFrom;
    }

    /**
     * @param DateTime $timeLimitFrom
     * @return UserDTO
     */
    public function setTimeLimitFrom(DateTime $timeLimitFrom): UserDTO
    {
        $this->timeLimitFrom = $timeLimitFrom->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeLimitUntil(): string
    {
        return $this->timeLimitUntil;
    }

    /**
     * @param DateTime $timeLimitUntil
     * @return UserDTO
     */
    public function setTimeLimitUntil(DateTime $timeLimitUntil): UserDTO
    {
        $this->timeLimitUntil = $timeLimitUntil->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return string
     */
    public function getMatriculation(): string
    {
        return $this->matriculation;
    }

    /**
     * @param string $matriculation
     * @return UserDTO
     */
    public function setMatriculation(string $matriculation): UserDTO
    {
        $this->matriculation = $matriculation;

        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     * @return UserDTO
     */
    public function setBirthday(DateTime $birthday): UserDTO
    {
        $this->birthday = $birthday->format(Config::SQL_DATE_FORMAT);

        return $this;
    }

    /**
     * @return array
     */
    public function getIliasRoles(): array
    {
        return $this->iliasRoles;
    }

    /**
     * @param array $iliasRoles
     * @return UserDTO
     */
    public function setIliasRoles(array $iliasRoles): UserDTO
    {
        $this->iliasRoles = $iliasRoles;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthMode()
    {
        return $this->authMode;
    }

    /**
     * @param string $authMode
     */
    public function setAuthMode(string $authMode): UserDTO
    {
        if (!in_array($authMode, self::$auth_modes)) {
            throw new InvalidArgumentException("'$authMode' is not a valid account type");
        }
        $this->authMode = $authMode;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalAccount(): string
    {
        return $this->externalAccount;
    }

    /**
     * @param string $externalAccount
     * @return UserDTO $this
     */
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
