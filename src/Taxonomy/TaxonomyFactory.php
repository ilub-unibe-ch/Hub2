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

use ilHub2Plugin;

use srag\Plugins\Hub2\Taxonomy\Node\INode;
use srag\Plugins\Hub2\Taxonomy\Node\Node;


/**
 * Class TaxonomyFactory
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class TaxonomyFactory implements ITaxonomyFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * @inheritdoc
     */
    public function select(string $title): ITaxonomy
    {
        return new Taxonomy($title, ITaxonomy::MODE_SELECT);
    }

    /**
     * @inheritdoc
     */
    public function create(string $title): ITaxonomy
    {
        return new Taxonomy($title, ITaxonomy::MODE_CREATE);
    }

    /**
     * @inheritdoc
     */
    public function node(string $node_title): INode
    {
        return new Node($node_title);
    }
}
