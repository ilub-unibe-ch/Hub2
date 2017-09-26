<?php namespace SRAG\Hub2\Origin;

use SRAG\Hub2\Origin\Config\UserOriginConfig;
use SRAG\Hub2\Origin\Properties\UserOriginProperties;

/**
 * Class ARUserOrigin
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\Hub2\Origin
 */
class ARUserOrigin extends AROrigin implements IUserOrigin {

	/**
	 * @inheritdoc
	 */
	protected function getOriginConfig(array $data) {
		return new UserOriginConfig($data);
	}


	/**
	 * @inheritdoc
	 */
	protected function getOriginProperties(array $data) {
		return new UserOriginProperties($data);
	}
}