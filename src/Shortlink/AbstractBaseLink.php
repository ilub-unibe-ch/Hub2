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

namespace srag\Plugins\Hub2\Shortlink;

use ilHub2Plugin;

use srag\Plugins\Hub2\Object\ARObject;

/**
 * Class AbstractBaseLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractBaseLink implements IObjectLink
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    protected \ilCtrlInterface $ctrl;
    protected \ilTree $tree;
    protected \ilAccessHandler $access;
    /**
     * @var ARObject
     */
    protected ARObject $object;

    /**
     * AbstractBaseLink constructor
     * @param ARObject $object
     */
    public function __construct(ARObject $object)
    {
        global $DIC;

        $this->access = $DIC->access();
        $this->tree = $DIC->repositoryTree();
        $this->ctrl = $DIC->ctrl();

        $this->object = $object;
    }

    /**
     * @inheritdoc
     */
    public function getNonExistingLink(): string
    {
        return "index.php";
    }
}
