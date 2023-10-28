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
 * Interface ITaxonomy
 * @package srag\Plugins\Hub2\Taxonomy
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface ITaxonomy
{
    public const MODE_SELECT = 1;
    public const MODE_CREATE = 2;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return int ITaxonomy::MODE_SELECT or ITaxonomy::MODE_CREATE
     */
    public function getMode(): int;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     * @return INode
     */
    public function setDescription(string $description): INode;

    /**
     * @return INode[]
     */
    public function getNodes(): array;

    /**
     * @return string[]
     */
    public function getNodeTitlesAsArray(): array;

    /**
     * @param INode $node
     * @return ITaxonomy
     */
    public function attach(INode $node): ITaxonomy;

    /**
     * @return string
     */
    public function __toString(): string;
}
