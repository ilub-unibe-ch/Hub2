<?php

namespace SRAG\Plugins\Hub2\Sync\Processor\OrgUnit;

use ilObject;
use SRAG\Plugins\Hub2\Helper\DIC;
use SRAG\Plugins\Hub2\Log\ILog;
use SRAG\Plugins\Hub2\Notification\OriginNotifications;
use SRAG\Plugins\Hub2\Object\DTO\IDataTransferObject;
use SRAG\Plugins\Hub2\Origin\Config\IOrgUnitOriginConfig;
use SRAG\Plugins\Hub2\Origin\IOrigin;
use SRAG\Plugins\Hub2\Origin\IOriginImplementation;
use SRAG\Plugins\Hub2\Origin\Properties\IOrgUnitOriginProperties;
use SRAG\Plugins\Hub2\Sync\IObjectStatusTransition;
use SRAG\Plugins\Hub2\Sync\Processor\ObjectSyncProcessor;

/**
 * Class OrgUnitSyncProcessor
 *
 * @package SRAG\Plugins\Hub2\Sync\Processor\OrgUnit
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OrgUnitSyncProcessor extends ObjectSyncProcessor implements IOrgUnitSyncProcessor {

	use DIC;
	/**
	 * @var IOrgUnitOriginProperties
	 */
	private $props;
	/**
	 * @var IOrgUnitOriginConfig
	 */
	private $config;
	/**
	 * @var array
	 */
	protected static $properties = [];


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
	 * @return array
	 */
	public static function getProperties(): array {
		return self::$properties;
	}


	/**
	 * @inheritdoc
	 */
	protected function handleCreate(IDataTransferObject $dto): ilObject {

	}


	/**
	 * @inheritdoc
	 */
	protected function handleUpdate(IDataTransferObject $dto, $ilias_id): ilObject {

	}


	/**
	 * @inheritdoc
	 */
	protected function handleDelete($ilias_id): ilObject {

	}
}
