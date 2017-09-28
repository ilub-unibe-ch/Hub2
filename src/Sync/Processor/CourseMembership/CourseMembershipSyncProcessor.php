<?php

namespace SRAG\Hub2\Sync\Processor\CourseMembership;

use SRAG\Hub2\Log\ILog;
use SRAG\Hub2\Notification\OriginNotifications;
use SRAG\Hub2\Object\CourseMembership\CourseMembershipDTO;
use SRAG\Hub2\Object\IDataTransferObject;
use SRAG\Hub2\Origin\Config\CourseOriginConfig;
use SRAG\Hub2\Origin\IOrigin;
use SRAG\Hub2\Origin\IOriginImplementation;
use SRAG\Hub2\Origin\Properties\CourseOriginProperties;
use SRAG\Hub2\Sync\IObjectStatusTransition;
use SRAG\Hub2\Sync\Processor\FakeIliasObject;
use SRAG\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class CourseMembershipSyncProcessor
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Sync\Processor
 */
class CourseMembershipSyncProcessor extends ObjectSyncProcessor implements ICourseMembershipSyncProcessor {

	const SPLIT = "|||";
	/**
	 * @var CourseOriginProperties
	 */
	protected $props;
	/**
	 * @var CourseOriginConfig
	 */
	protected $config;


	/**
	 * @param IOrigin                 $origin
	 * @param IOriginImplementation   $implementation
	 * @param IObjectStatusTransition $transition
	 * @param ILog                    $originLog
	 * @param OriginNotifications     $originNotifications
	 */
	public function __construct(IOrigin $origin, IOriginImplementation $implementation, IObjectStatusTransition $transition, ILog $originLog, OriginNotifications $originNotifications) {
		parent::__construct($origin, $implementation, $transition, $originLog, $originNotifications);
		$this->props = $origin->properties();
		$this->config = $origin->config();
	}


	/**
	 * @inheritdoc
	 */
	protected function handleCreate(IDataTransferObject $object) {
		/**
		 * @var $object \SRAG\Hub2\Object\CourseMembership\CourseMembershipDTO
		 */
		$ilias_course_ref_id = $object->getIliasCourseRefId();
		$course = $this->findILIASCourse($ilias_course_ref_id);
		if (!$course) {
			return null;
		}
		$user_id = $object->getUserId();
		$course->getMembersObject()->add($user_id, $this->mapRole($object));

		return new FakeIliasObject("{$user_id}" . self::SPLIT . "{$ilias_course_ref_id}");
	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $object, $ilias_id) {
		/**
		 * @var $object \SRAG\Hub2\Object\CourseMembership\CourseMembershipDTO
		 */
		$ilias_course_ref_id = $object->getIliasCourseRefId();
		$user_id = $object->getUserId();
		if (!$this->props->updateDTOProperty('role')) {
			return new FakeIliasObject("{$user_id}" . self::SPLIT . "{$ilias_course_ref_id}");
		}

		$course = $this->findILIASCourse($ilias_course_ref_id);
		if (!$course) {
			return null;
		}

		$course->getMembersObject()
		       ->updateRoleAssignments($user_id, [ $this->getILIASRole($object, $course) ]);

		return new FakeIliasObject("{$user_id}" . self::SPLIT . "{$ilias_course_ref_id}");
	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id) {
		list ($user_id, $ilias_course_ref_id) = explode(self::SPLIT, $ilias_id);
		$course = $this->findILIASCourse($ilias_course_ref_id);
		$course->getMembersObject()->delete($user_id);

		return new FakeIliasObject("{$user_id}" . self::SPLIT . "{$ilias_course_ref_id}");
	}


	/**
	 * @param int $iliasId
	 *
	 * @return \ilObjCourse|null
	 */
	protected function findILIASCourse($iliasId) {
		if (!\ilObjCourse::_exists($iliasId, true)) {
			return null;
		}

		return new \ilObjCourse($iliasId);
	}


	/**
	 * @param $object CourseMembershipDTO
	 *
	 * @return int
	 */
	protected function mapRole(CourseMembershipDTO $object) {
		switch ($object->getRole()) {
			case CourseMembershipDTO::ROLE_ADMIN:
				return IL_CRS_ADMIN;
			case CourseMembershipDTO::ROLE_TUTOR:
				return IL_CRS_TUTOR;
			case CourseMembershipDTO::ROLE_MEMBER:
				return IL_CRS_MEMBER;
			default:
				return IL_CRS_MEMBER;
		}
	}


	/**
	 * @param \SRAG\Hub2\Object\CourseMembership\CourseMembershipDTO $object
	 * @param \ilObjCourse                                           $course
	 *
	 * @return int
	 */
	protected function getILIASRole(CourseMembershipDTO $object, \ilObjCourse $course) {
		switch ($object->getRole()) {
			case CourseMembershipDTO::ROLE_ADMIN:
				return $course->getDefaultAdminRole();
			case CourseMembershipDTO::ROLE_TUTOR:
				return $course->getDefaultTutorRole();
			case CourseMembershipDTO::ROLE_MEMBER:
				return $course->getDefaultMemberRole();
			default:
				return $course->getDefaultMemberRole();
		}
	}
}