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

namespace srag\Plugins\Hub2\Jobs\Result;

use ilCronJobResult;
use ilHub2Plugin;
/**
 * Class AbstractResult
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractResult extends ilCronJobResult
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    public const STATUS_OK = 3;
    public const STATUS_CRASHED = 4;

    /**
     * AbstractResult constructor
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->setMessage($message);
        $this->initStatus();
    }

    /**
     * inits the status to STATUS_OK or STATUS_CRASHED
     */
    abstract protected function initStatus();
}
