<?php

namespace srag\Plugins\Hub2\Jobs\Result;

use Throwable;

/**
 * Class Error
 *
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Error extends AbstractResult {

    /**
     * @var Throwable
     */
    protected $error = null;

	/**
	 * @inheritdoc
	 */
	protected function initStatus() {
		$this->setStatus(self::STATUS_CRASHED);
	}

	public function setError(Throwable $e){
	    $this->error = $e;
    }

    /**
     * @return Throwable
     */
    public function getError(){
	    return $this->error;
    }
}
