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

namespace srag\Plugins\Hub2\Taxonomy\Node;

use ilHub2Plugin;
/**
 * Class Node
 * @package srag\Plugins\Hub2\Taxonomy\Node
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Node implements INode
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var string
     */
    protected string $title = '';
    /**
     * @var INode[]
     */
    protected array $nodes = [];

    /**
     * Node constructor
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @inheritdoc
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @inheritdoc
     */
    public function getNodeTitlesAsArray(): array
    {
        $titles = [];
        foreach ($this->nodes as $node) {
            $titles[] = $node->getTitle();
        }

        return $titles;
    }

    /**
     * @inheritdoc
     */
    public function attach(INode $node): INode
    {
        $this->nodes[] = $node;

        return $this;
    }
}
