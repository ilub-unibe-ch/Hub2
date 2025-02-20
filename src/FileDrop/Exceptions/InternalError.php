<?php

namespace srag\Plugins\Hub2\FileDrop\Exceptions;

use Exception;

/**
 * Class InternalError
 *
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class InternalError extends Exception
{
    protected $message = 'Internal Error';

    public function __construct($message)
    {
        parent::__construct($this->message . ': ' . $message);
    }

}
