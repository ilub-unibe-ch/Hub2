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

use ilContainer;
use ilObject2;
use ilObjectServiceSettingsGUI;
use ilObjTaxonomy;
use ilTaxNodeAssignment;
use ilTaxonomyException;
use srag\Plugins\Hub2\Exception\TaxonomyNodeNotFoundException;
use srag\Plugins\Hub2\Exception\TaxonomyNotFoundException;
use srag\Plugins\Hub2\Taxonomy\Node\INode;

/**
 * Class TaxonomySelect
 * @package srag\Plugins\Hub2\Taxonomy\Implementation
 */
class TaxonomySelect extends AbstractTaxonomy implements ITaxonomyImplementation
{
    /**
     * @var int
     */
    protected int $container_obj_id;
    /**
     * @var ilTaxNodeAssignment
     */
    protected ilTaxNodeAssignment $ilTaxNodeAssignment;
    /**
     * @var array
     */
    protected array $selectable_taxonomies = [];

    /**
     * @inheritdoc
     */
    public function write()
    {
        $this->initSelectableTaxonomies();

        if (!$this->taxonomyExists()) {
            throw new TaxonomyNotFoundException($this->getTaxonomy());
        }
        $this->selectTaxonomy();
        $this->handleNodes();
    }

    /**
     *
     */
    protected function handleNodes()
    {
        $this->initTaxTree();
        foreach ($this->getTaxonomy()->getNodes() as $node) {
            if (!$this->nodeExists($node)) {
                throw new TaxonomyNodeNotFoundException($node);
            }
            $this->selectNode($node);
        }
    }

    /**
     *
     */
    private function selectTaxonomy()
    {
        $tax_id = array_search($this->getTaxonomy()->getTitle(), $this->selectable_taxonomies);
        if (!$tax_id) {
            throw new TaxonomyNotFoundException($this->getTaxonomy());
        }
        $this->ilObjTaxonomy = new ilObjTaxonomy($tax_id);
        $this->container_obj_id = ilObject2::_lookupObjId($this->getILIASParentId());
        $a_component_id = ilObject2::_lookupType($this->container_obj_id);
        $this->ilTaxNodeAssignment = new ilTaxNodeAssignment($a_component_id, $this->container_obj_id, "obj", $tax_id);
    }

    /**
     * @param INode $node
     * @throws TaxonomyNodeNotFoundException
     * @throws ilTaxonomyException
     */
    private function selectNode(INode $node)
    {
        $node_id = array_search($node->getTitle(), $this->childs);
        if (!$node_id) {
            throw new TaxonomyNodeNotFoundException($node);
        }

        $this->ilTaxNodeAssignment->addAssignment($node_id, $this->container_obj_id);
    }

    /**
     * @inheritdoc
     */
    protected function taxonomyExists(): bool
    {
        return in_array($this->getTaxonomy()->getTitle(), $this->selectable_taxonomies);
    }

    /**
     *
     */
    private function initSelectableTaxonomies()
    {
        $res = [];
        foreach (self::dic()->tree()->getPathFull($this->getILIASParentId()) as $node) {
            if ($node["ref_id"] != $this->getILIASParentId()) {
                if ($node["type"] == "cat") {
                    if (ilContainer::_lookupContainerSetting(
                        $node["obj_id"],
                        ilObjectServiceSettingsGUI::TAXONOMIES,
                        false
                    )) {
                        $tax_ids = ilObjTaxonomy::getUsageOfObject($node["obj_id"]);
                        if (sizeof($tax_ids)) {
                            $res = array_merge($res, $tax_ids);
                        }
                    }
                }
            }
        }
        foreach ($res as $re) {
            $this->selectable_taxonomies[$re] = ilObject2::_lookupTitle($re);
        }
    }
}
