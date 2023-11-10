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

namespace srag\Plugins\Hub2\Sync\Processor\User;

use ilMimeMail;
use ilObjUser;
use ilUtil;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\User\UserDTO;
use srag\Plugins\Hub2\Origin\Config\User\IUserOriginConfig;
use srag\Plugins\Hub2\Origin\Config\User\UserOriginConfig;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Origin\Properties\User\UserProperties;
use srag\Plugins\Hub2\Sync\IObjectStatusTransition;
use srag\Plugins\Hub2\Sync\Processor\MetadataSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;
use srag\Plugins\Hub2\Origin\Properties\User\IUserProperties;
use ilSecuritySettingsChecker;

/**
 * Class UserProcessor
 * @package srag\Plugins\Hub2\Sync\Processor\User
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class UserSyncProcessor extends ObjectSyncProcessor implements IUserSyncProcessor
{
    use MetadataSyncProcessor;

    /**
     * @var UserProperties
     */
    private $props;
    /**
     * @var UserOriginConfig
     */
    private $config;
    /**
     * @var array
     */
    protected static array $properties = [
        'authMode',
        'externalAccount',
        'firstname',
        'lastname',
        'email',
        'institution',
        'street',
        'city',
        'zipcode',
        'country',
        'selectedCountry',
        'phoneOffice',
        'phoneHome',
        'phoneMobile',
        'department',
        'fax',
        'timeLimitOwner',
        'timeLimitUnlimited',
        'timeLimitFrom',
        'timeLimitUntil',
        'matriculation',
        'gender',
        'birthday',
    ];

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $transition
     */
    public function __construct(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $transition
    ) {
        parent::__construct($origin, $implementation, $transition);
        $this->props = $origin->properties();
        $this->config = $origin->config();
    }

    /**
     * @return array
     */
    public static function getProperties(): array
    {
        return self::$properties;
    }

    /**
     * @inheritdoc
     * @param UserDTO $dto
     */
    protected function handleCreate(IDataTransferObject $dto)/*: void*/
    {
        $this->current_ilias_object = $ilObjUser = new ilObjUser();
        $ilObjUser->setTitle($dto->getFirstname() . ' ' . $dto->getLastname());
        $ilObjUser->setDescription($dto->getEmail());
        $ilObjUser->setImportId($this->getImportId($dto));
        $ilObjUser->setLogin($this->buildLogin($dto, $ilObjUser));
        $ilObjUser->setUTitle((string)$dto->getTitle());
        $ilObjUser->create();
        if ($this->props->get(IUserProperties::ACTIVATE_ACCOUNT)) {
            $ilObjUser->setActive(true);
            $ilObjUser->setProfileIncomplete(false);
        } else {
            $ilObjUser->setActive(false);
            $ilObjUser->setProfileIncomplete(true);
        }
        if ($this->props->get(IUserProperties::CREATE_PASSWORD)) {
            $password = $this->generatePassword();
            $ilObjUser->setPasswd($password);
        } else {
            $ilObjUser->setPasswd($dto->getPasswd());
        }
        foreach (self::getProperties() as $property) {
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjUser->$setter($dto->$getter());
            }
        }

        if ($this->props->get(IUserProperties::CREATE_PASSWORD)) {
            $password = $this->generatePassword();
            $dto->setPasswd($password);
            $ilObjUser->setPasswd($dto->getPasswd());
            $this->sendPasswordMail($dto);
        } else {
            $ilObjUser->setPasswd($dto->getPasswd());
        }

        $ilObjUser->saveAsNew();
        $ilObjUser->writePrefs();
        $this->assignILIASRoles($dto, $ilObjUser);
    }

    /**
     * @inheritdoc
     * @param UserDTO $dto
     */
    protected function handleUpdate(IDataTransferObject $dto, string $ilias_id): void
    {
        $this->current_ilias_object = $ilObjUser = $this->findILIASUser((int)$ilias_id);
        if ($ilObjUser === null) {
            // Recreate deleted users
            $this->handleCreate($dto);

            return;
        }
        $ilObjUser->setImportId($this->getImportId($dto));
        $ilObjUser->setTitle($dto->getFirstname() . ' ' . $dto->getLastname());
        $ilObjUser->setDescription($dto->getEmail());
        // Update Login?
        if ($this->props->updateDTOProperty('login')) {
            $ilObjUser->updateLogin($this->buildLogin($dto, $ilObjUser));
        }
        // Update title?
        if ($this->props->updateDTOProperty('title')) {
            $ilObjUser->setUTitle($dto->getTitle());
        }
        // Reactivate account?
        if ($this->props->get(IUserProperties::REACTIVATE_ACCOUNT)) {
            $ilObjUser->setActive(true);
        }
        // Set all properties if they should be updated depending on the origin config
        foreach (self::getProperties() as $property) {
            if (!$this->props->updateDTOProperty($property)) {
                continue;
            }
            $setter = "set" . ucfirst($property);
            $getter = "get" . ucfirst($property);
            if ($dto->$getter() !== null) {
                $ilObjUser->$setter($dto->$getter());
            }
        }
        // Update ILIAS roles ?
        if ($this->props->updateDTOProperty('iliasRoles')) {
            $this->assignILIASRoles($dto, $ilObjUser);
        }

        if ($this->props->get(IUserProperties::RE_SEND_PASSWORD)) {
            $password = $this->generatePassword();
            $dto->setPasswd($password);
            $ilObjUser->setPasswd($dto->getPasswd());
            $this->sendPasswordMail($dto);
        }

        $ilObjUser->update();
    }

    private function sendPasswordMail(IDataTransferObject $dto)
    {
        /** @var UserDTO $dto */

        $mail_field = $dto->getEmail();
        if ($mail_field) {
            $mail = new ilMimeMail();
            $mail->From($this->sender_factory->system());
            $mail->To($dto->getEmail());
            $body = $this->props->get(IUserProperties::PASSWORD_MAIL_BODY);

            $body = strtr($body, [
                '[PASSWORD]' => $dto->getPasswd(),
                '[LOGIN]' => $dto->getLogin()
            ]);
            $mail->Subject($this->props->get(IUserProperties::PASSWORD_MAIL_SUBJECT)); // TODO: Also replace placeholders
            $mail->Body($body);
            $mail->Send();
        }
    }

    /**
     * @inheritdoc
     */
    protected function handleDelete(string $ilias_id): void
    {
        $this->current_ilias_object = $ilObjUser = $this->findILIASUser((int)$ilias_id);
        if ($ilObjUser === null) {
            return;
        }
        if ($this->props->get(IUserProperties::DELETE) == IUserProperties::DELETE_MODE_NONE) {
            return;
        }
        switch ($this->props->get(IUserProperties::DELETE)) {
            case IUserProperties::DELETE_MODE_INACTIVE:
                $ilObjUser->setActive(false);
                $ilObjUser->update();
                break;
            case IUserProperties::DELETE_MODE_DELETE:
                $ilObjUser->delete();
                break;
        }
    }

    /**
     * @param UserDTO   $user
     * @param ilObjUser $ilObjUser
     */
    protected function assignILIASRoles(UserDTO $user, ilObjUser $ilObjUser)
    {
        foreach ($user->getIliasRoles() as $role_id) {
            $this->rbac_admin->assignUser($role_id, $ilObjUser->getId());
        }
    }

    /**
     * Build the login name depending on the origin properties
     * @param UserDTO   $user
     * @param ilObjUser $ilObjUser
     * @return string
     */
    protected function buildLogin(UserDTO $user, ilObjUser $ilObjUser): string
    {
        switch ($this->config->getILIASLoginField()) {
            case IUserOriginConfig::LOGIN_FIELD_EMAIL:
                $login = $user->getEmail();
                break;
            case IUserOriginConfig::LOGIN_FIELD_EXT_ACCOUNT:
                $login = $user->getExternalAccount();
                break;
            case IUserOriginConfig::LOGIN_FIELD_EXT_ID:
                $login = $user->getExtId();
                break;
            case IUserOriginConfig::LOGIN_FIELD_FIRSTNAME_LASTNAME:
                $login = $this->clearString($user->getFirstname()) . '.' . $this->clearString($user->getLastname());
                break;
            case IUserOriginConfig::LOGIN_FIELD_HUB_LOGIN:
                $login = $user->getLogin();
                break;
            case IUserOriginConfig::LOGIN_FIELD_SHORTENED_FIRST_LASTNAME:
                $login = substr(
                    $this->clearString($user->getFirstname()),
                    0,
                    1
                ) . '.' . $this->clearString($user->getLastname());
                break;
            default:
                $login = substr(
                    $this->clearString($user->getFirstname()),
                    0,
                    1
                ) . '.' . $this->clearString($user->getLastname());
        }
        $login = mb_strtolower($login);

        // We need to make sure the login is unique, note that ILIAS does this currently only on GUI level -.-
        $appendix = 2;
        $_login = $login;
        while (ilObjUser::_loginExists($login, $ilObjUser->getId())) {
            $login = $_login . $appendix;
            $appendix++;
        }

        $user->setLogin($login);

        return $login;
    }

    /**
     * @param int $ilias_id
     * @return ilObjUser|null
     */
    protected function findILIASUser(int $ilias_id): ?ilObjUser
    {
        if (!ilObjUser::_exists($ilias_id)) {
            return null;
        }

        return new ilObjUser($ilias_id);
    }

    /**
     * @return string
     */
    protected function generatePassword(): string
    {
        $array = ilSecuritySettingsChecker::generatePasswords(1);
        return array_pop($array);
    }
}
