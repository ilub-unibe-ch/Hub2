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
use ilObjTaxonomy;
use ilTaxonomyTree;

use srag\Plugins\Hub2\Taxonomy\ITaxonomy;
use srag\Plugins\Hub2\Taxonomy\Node\INode;


/**
 * Class AbstractTaxonomy
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractTaxonomy implements ITaxonomyImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var int
     */
    protected int $tree_root_id;
    /**
     * @var ilTaxonomyTree
     */
    protected ilTaxonomyTree $tree;
    /**
     * @var array
     */
    protected array $childs = [];
    /**
     * @var ilObjTaxonomy
     */
    protected ilObjTaxonomy $ilObjTaxonomy;
    /**
     * @var ITaxonomy
     */
    protected ITaxonomy $taxonomy;
    /**
     * @var int
     */
    protected int $ilias_parent_id;

    /**
     * Taxonomy constructor
     * @param ITaxonomy $taxonomy
     */
    public function __construct(ITaxonomy $taxonomy, int $ilias_parent_id)
    {
        $this->taxonomy = $taxonomy;
        $this->ilias_parent_id = $ilias_parent_id;
    }

    /**
     * @return bool
     */
    protected function taxonomyExists(): bool
    {
        $childsByType = self::dic()->tree()->getChildsByType($this->getILIASParentId(), 'tax');
        if (!count($childsByType)) {
            return false;
        }
        foreach ($childsByType as $value) {
            if ($value["title"] === $this->getTaxonomy()->getTitle()) {
                $this->ilObjTaxonomy = new ilObjTaxonomy($value["obj_id"]);

                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    protected function initTaxTree()
    {
        $this->tree = $this->ilObjTaxonomy->getTree();
        $this->tree_root_id = $this->tree->readRootId();
        $this->setChildrenByParentId($this->tree_root_id);
    }

    /**
     * @param int $parent_id
     */
    protected function setChildrenByParentId(int $parent_id)
    {
        foreach ($this->tree->getChildsByTypeFilter($parent_id, ["taxn"]) as $item) {
            $this->childs[$item['obj_id']] = $item['title'];
            $this->setChildrenByParentId($item['obj_id']);
        }
    }

    /**
     * @param INode $node
     * @return bool
     */
    protected function nodeExists(INode $node): bool
    {
        return in_array($node->getTitle(), $this->childs);
    }

    /**
     * @inheritdoc
     */
    abstract public function write();

    /**
     * @inheritdoc
     */
    public function getTaxonomy(): ITaxonomy
    {
        return $this->taxonomy;
    }

    /**
     * @inheritdoc
     */
    public function getILIASParentId(): int
    {
        return $this->ilias_parent_id;
    }
}
