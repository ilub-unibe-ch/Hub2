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

namespace srag\Plugins\Hub2\MappingStrategy;

use ilHub2Plugin;
/**
 * Class MappingStrategyFactory
 * @package srag\Plugins\Hub2\MappingStrategy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class MappingStrategyFactory implements IMappingStrategyFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function byEmail(): IMappingStrategy
    {
        return new ByEmail();
    }

    /**
     * @inheritdoc
     */
    public function byLogin(): IMappingStrategy
    {
        return new ByLogin();
    }

    /**
     * @inheritdoc
     */
    public function byExternalAccount(): IMappingStrategy
    {
        return new ByExternalAccount();
    }

    /**
     * @inheritdoc
     */
    public function byTitle(): IMappingStrategy
    {
        return new ByTitle();
    }

    /**
     * @inheritdoc
     */
    public function byImportId(): IMappingStrategy
    {
        return new ByImportId();
    }

    /**
     * @inheritdoc
     */
    public function none(): IMappingStrategy
    {
        return new None();
    }
}
