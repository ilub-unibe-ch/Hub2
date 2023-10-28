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

namespace srag\Plugins\Hub2\Object\DTO;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;

/**
 * Class TaxonomyAwareDataTransferObject
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomyAwareDataTransferObject
{
    /**
     * @var array
     */
    private array $_taxonomies = [];

    /**
     * @inheritdoc
     */
    public function addTaxonomy(ITaxonomy $ITaxonomy): ITaxonomyAwareDataTransferObject
    {
        $this->_taxonomies[] = $ITaxonomy;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTaxonomies(): array
    {
        return $this->_taxonomies;
    }
}
