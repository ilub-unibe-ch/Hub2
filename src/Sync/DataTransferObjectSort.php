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

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;

use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;


/**
 * Class DataTransferObjectSort
 * @package srag\Plugins\Hub2\Sync
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class DataTransferObjectSort implements IDataTransferObjectSort
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IDataTransferObject
     */
    private IDataTransferObject $dto_object;
    /**
     * @var int
     */
    private int $level = 1;

    /**
     * @param IDataTransferObject $dto_object
     */
    public function __construct(IDataTransferObject $dto_object)
    {
        $this->dto_object = $dto_object;
    }

    /**
     * @inheritdoc
     */
    public function getDtoObject(): IDataTransferObject
    {
        return $this->dto_object;
    }

    public function setDtoObject(IDataTransferObject $dto_object)
    {
        $this->dto_object = $dto_object;
    }

    /**
     * @inheritdoc
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @inheritdoc
     */
    public function setLevel(int $level)
    {
        $this->level = $level;
    }
}
