<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Sync\GlobalHook;

use srag\Plugins\Hub2\Log\ILog;
use Throwable;

/**
 * Interface IGlobalHook
 * @package srag\Plugins\Hub2\Sync\GlobalHook
 * @author  Timon Amstutz
 */
interface IGlobalHook
{
    /**
     * This is executed before all active origins are synced.
     * @param array $active_orgins all active origins that will be exectued
     * @return bool
     */
    public function beforeSync(array $active_orgins): bool;

    /**
     * This is executed after all active origins have been.
     * @param array $active_orgins $active_orgins all active origins that have been executed.
     * @return bool
     */
    public function afterSync(array $active_orgins): bool;

    /**
     * This is executed after afterSync and allows the custom processing of exceptions fired during the sync.
     * @param ILog $log
     */
    public function handleLog(ILog $log);

    /**
     * This is executed after afterSync and allows the custom processing of exceptions fired during the sync.
     * @param Throwable $throwable
     */
    public function handleThrowable(Throwable $throwable);
}
