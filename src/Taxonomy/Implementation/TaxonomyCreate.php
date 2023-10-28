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

use ilObject2;
use ilObjTaxonomy;
use ilTaxonomyNode;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomyCreate
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomyCreate extends AbstractTaxonomy implements ITaxonomyImplementation
{

    /**
     * @inheritdoc
     */
    public function write()
    {
        if (!$this->taxonomyExists()) {
            $this->createTaxonomy();
        }

        ilObjTaxonomy::saveUsage($this->ilObjTaxonomy->getId(), ilObject2::_lookupObjId($this->getILIASParentId()));
        $this->handleNodes();
    }

    private function createTaxonomy()
    {
        $tax = new ilObjTaxonomy();
        $tax->setTitle($this->getTaxonomy()->getTitle());
        $tax->setDescription($this->getTaxonomy()->getDescription());
        $tax->create();
        $tax->createReference();
        $tax->putInTree($this->getILIASParentId());
        $tax->setPermissions($this->getILIASParentId());

        $this->ilObjTaxonomy = $tax;
    }

    protected function handleNodes()
    {
        $this->initTaxTree();
        foreach ($this->getTaxonomy()->getNodes() as $node) {
            if (!$this->nodeExists($node)) {
                $this->createNode($node);
            }
        }
    }

    /**
     * @param INode $nodeDTO
     */
    private function createNode(INode $nodeDTO, $parent_id = 0)
    {
        $node = new ilTaxonomyNode();
        $node->setTitle($nodeDTO->getTitle());
        $node->setOrderNr(1);
        $node->setTaxonomyId($this->ilObjTaxonomy->getId());
        $node->create();

        if ($parent_id == 0) {
            ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $this->tree_root_id);
            ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $this->tree_root_id);
        } else {
            ilTaxonomyNode::putInTree($this->ilObjTaxonomy->getId(), $node, $parent_id);
            ilTaxonomyNode::fixOrderNumbers($this->ilObjTaxonomy->getId(), $parent_id);
        }

        foreach ($nodeDTO->getNodes() as $node_dto) {
            $this->createNode($node_dto, $node->getId());
        }
    }
}
