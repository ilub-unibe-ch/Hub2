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

use Throwable;

/**
 * Class Error
 * @package srag\Plugins\Hub2\Jobs\Result
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Error extends AbstractResult
{
    /**
     * @var Throwable
     */
    protected ?Throwable $error = null;

    /**
     * @inheritdoc
     */
    protected function initStatus()
    {
        $this->setStatus(self::STATUS_CRASHED);
    }

    public function setError(Throwable $e)
    {
        $this->error = $e;
    }

    /**
     * @return Throwable
     */
    public function getError(): ?Throwable
    {
        return $this->error;
    }
}
