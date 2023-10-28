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

namespace srag\Plugins\Hub2\Origin\Category;

use srag\Plugins\Hub2\Origin\AROrigin;
use srag\Plugins\Hub2\Origin\Config\Category\CategoryOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\Category\CategoryProperties;

/**
 * Class ARCategoryOrigin
 * @package srag\Plugins\Hub2\Origin\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ARCategoryOrigin extends AROrigin implements ICategoryOrigin
{
    /**
     * @inheritdoc
     */
    protected function getOriginConfig(array $data): CategoryOriginConfig
    {
        return new CategoryOriginConfig($data);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginProperties(array $data): CategoryProperties
    {
        return new CategoryProperties($data);
    }
}
