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

namespace srag\Plugins\Hub2\Config;

/**
 * Class ActiveRecordConfigFactory
 *
 * @package    srag\ActiveRecordConfig\Hub2
 *
 * @author     studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated Do not use - only used for be compatible with old version
 */
final class ActiveRecordConfigFactory extends AbstractFactory
{
    /**
     * @var self|null
     *
     * @deprecated
     */
    protected static ?ActiveRecordConfigFactory $instance;

    /**
     * @deprecated
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance) ||  !(self::$instance instanceof ActiveRecordConfigFactory)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
