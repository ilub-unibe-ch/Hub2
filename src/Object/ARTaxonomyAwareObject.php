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

namespace srag\Plugins\Hub2\Object;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class ARTaxonomyAwareObject
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait ARTaxonomyAwareObject
{
    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    clob
     */
    protected array $taxonomies = [];

    /**
     * @return ITaxonomy[]
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * @param ITaxonomy[] $taxonomies
     */
    public function setTaxonomies(array $taxonomies)
    {
        $this->taxonomies = $taxonomies;
    }
}
