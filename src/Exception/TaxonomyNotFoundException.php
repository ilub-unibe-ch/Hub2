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

namespace srag\Plugins\Hub2\Exception;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class TaxonomyNotFoundException
 * @package srag\Plugins\Hub2\Exception
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyNotFoundException extends HubException
{
    /**
     * @var ITaxonomy
     */
    protected ITaxonomy $taxonomy;

    /**
     * TaxonomyNotFoundException constructor
     * @param ITaxonomy $ta
     */
    public function __construct(ITaxonomy $ta)
    {
        parent::__construct("ILIAS Taxonomy object not found for: {$ta->getTitle()}");
        $this->taxonomy = $ta;
    }

    /**
     * @return ITaxonomy
     */
    public function getTaxonomy(): ITaxonomy
    {
        return $this->taxonomy;
    }
}
