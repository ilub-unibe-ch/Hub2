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

/**
 * Class AbstractResult
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
final class ResultFactory
{
    /**
     * @param string $message
     * @return OK
     */
    public static function ok(string $message): ilCronJobResult
    {
        return new OK($message);
    }

    /**
     * @param string $message
     * @return Error
     */
    public static function error(string $message): ilCronJobResult
    {
        return new Error($message);
    }

    /**
     * ResultFactory constructor
     */
    private function __construct()
    {

    }
}
