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


/**
 * Class Taxonomy
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class Taxonomy implements ITaxonomy
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var INode[]
     */
    protected array $nodes = [];
    /**
     * @var string
     */
    protected string $title = '';
    /**
     * @var int
     */
    protected int $mode;
    /**
     * @var string
     */
    protected string $description = "";

    /**
     * Taxonomy constructor
     * @param string $title
     * @param int    $mode
     */
    public function __construct(string $title, int $mode)
    {
        $this->title = $title;
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getMode(): int
    {
        return $this->mode;
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
    public function attach(INode $node): ITaxonomy
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Taxonomy
     */
    public function setDescription(string $description): ITaxonomy
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return ""; // Is this needed?
    }
}
