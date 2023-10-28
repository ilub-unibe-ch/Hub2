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

namespace srag\Plugins\Hub2\Taxonomy;

use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class ITaxonomyFactory
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomyFactory
{
    /**
     * @param string $title
     * @return ITaxonomy
     */
    public function select(string $title): ITaxonomy;

    /**
     * @param string $title
     * @return ITaxonomy
     */
    public function create(string $title): ITaxonomy;

    /**
     * @param string $node_title
     * @return INode
     */
    public function node(string $node_title): INode;
}
