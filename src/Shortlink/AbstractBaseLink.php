<?php

namespace SRAG\Plugins\Hub2\Shortlink;

use ilHub2Plugin;
use srag\DIC\DICTrait;
use SRAG\Plugins\Hub2\Object\ARObject;

/**
 * Class NullLink
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractBaseLink implements IObjectLink {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
	/**
	 * @var ARObject
	 */
	protected $object;


	/**
	 * AbstractBaseLink constructor.
	 *
	 * @param ARObject $object
	 */
	public function __construct(ARObject $object) { $this->object = $object; }


	/**
	 * @inheritDoc
	 */
	public function getNonExistingLink(): string {
		return "index.php";
	}
}
