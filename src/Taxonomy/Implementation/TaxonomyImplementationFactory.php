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

namespace srag\Plugins\Hub2\Taxonomy\Implementation;

use ilHub2Plugin;
use ilObject;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;


/**
 * Class ITaxonomyImplementationFactory
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyImplementationFactory implements ITaxonomyImplementationFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function taxonomy(ITaxonomy $Taxonomy, ilObject $ilias_object): ?ITaxonomyImplementation
    {
        switch ($Taxonomy->getMode()) {
            case ITaxonomy::MODE_CREATE:
                return new TaxonomyCreate($Taxonomy, $ilias_object->getRefId());
            case ITaxonomy::MODE_SELECT:
                return new TaxonomySelect($Taxonomy, $ilias_object->getRefId());
        }

        return null;
    }
}
