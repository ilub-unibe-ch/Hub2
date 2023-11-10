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

namespace srag\Plugins\Hub2\Object\CourseMembership;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasMembershipObject;

/**
 * Class CourseMembershipDTO
 * @package srag\Plugins\Hub2\Object\CourseMembership
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseMembershipDTO extends DataTransferObject implements ICourseMembershipDTO
{
    /**
     * @inheritdoc
     */
    public function __construct($course_ext_id, $user_id)
    {
        parent::__construct(implode(FakeIliasMembershipObject::GLUE, [$course_ext_id, $user_id]));
        $this->courseId = $course_ext_id;
        $this->userId = $user_id;
    }

    public const ROLE_MEMBER = 2;
    public const ROLE_TUTOR = 3;
    public const ROLE_ADMIN = 1;
    public const COURSE_ID_TYPE_REF_ID = 1;
    public const COURSE_ID_TYPE_EXTERNAL_EXT_ID = 2;

    protected int $courseIdType = self::COURSE_ID_TYPE_REF_ID;
    protected string $courseId;
    protected int $userId;
    protected int $role = self::ROLE_MEMBER;
    protected bool $isContact = false;

    /**
     * @return int
     */
    public function getCourseId(): string
    {
        return $this->courseId;
    }

    /**
     * @param int $courseId
     * @return CourseMembershipDTO
     */
    public function setCourseId(string $courseId): CourseMembershipDTO
    {
        $this->courseId = $courseId;

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
     * @return CourseMembershipDTO
     */
    public function setUserId(int $userId): CourseMembershipDTO
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     * @return CourseMembershipDTO
     */
    public function setRole($role): CourseMembershipDTO
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return int
     */
    public function getCourseIdType(): int
    {
        return $this->courseIdType;
    }

    /**
     * @param int $courseIdType
     * @return CourseMembershipDTO
     */
    public function setCourseIdType(int $courseIdType): CourseMembershipDTO
    {
        $this->courseIdType = $courseIdType;

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
    public function setIsContact(bool $isContact): CourseMembershipDTO
    {
        $this->isContact = $isContact;

        return $this;
    }
}
