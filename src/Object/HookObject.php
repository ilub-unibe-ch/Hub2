<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *********************************************************************/

declare(strict_types=1);

namespace srag\Plugins\Hub2\Object;

use ilHub2Plugin;
use ilObject;

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Sync\Processor\FakeIliasObject;


/**
 * Class HookObject
 * @package srag\Plugins\Hub2\Object
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class HookObject
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IDataTransferObject
     */
    protected IDataTransferObject $dto;
    /**
     * @var IObject
     */
    private IObject $object;
    /**
     * @var ilObject|FakeIliasObject
     */
    private $ilias_object;

    /**
     * @param IObject             $object
     * @param IDataTransferObject $dto
     */
    public function __construct(IObject $object, IDataTransferObject $dto)
    {
        $this->object = $object;
        $this->dto = $dto;
    }

    /**
     * Get the external ID of the object helps to identify the object
     * @return string
     */
    public function getExtId(): string
    {
        return $this->object->getExtId();
    }

    /**
     * Get the current status, see constants in IObject
     * @return int
     */
    public function getStatus(): int
    {
        return $this->object->getStatus();
    }

    /**
     * @param int $status
     * @throws HubException
     */
    public function overrideStatus(int $status)
    {
        $this->object->setStatus($status);
    }

    /**
     * @param ilObject|FakeIliasObject $object
     * @return HookObject
     */
    public function withILIASObject($object): HookObject
    {
        $clone = clone $this;
        $clone->ilias_object = $object;

        return $clone;
    }

    /**
     * Get the ILIAS object which has been processed.
     * Note that this object is only available in the
     * IOriginImplementation::after(Create|Update|Delete)Object callbacks, it is NOT set for any
     * before callbacks
     * @return ilObject|FakeIliasObject
     */
    public function getILIASObject()
    {
        return $this->ilias_object;
    }

    /**
     * Get the ID of the linked ILIAS object.
     * Note that this ID may be the object or ref-ID depending on the synced object.
     * Also note that this ID may be NULL if the ILIAS object has not been created yet, e.g.
     * in the case of IOriginImplementation::beforeCreateILIASObject()
     * @return int
     */
    public function getILIASId(): ?string
    {
        return $this->object->getILIASId();
    }

    /**
     * @return IDataTransferObject
     */
    public function getDTO(): IDataTransferObject
    {
        return $this->dto;
    }

    /**
     * @return IObject the internal AR Object, not the ILIAS Object
     */
    public function getObject(): IObject
    {
        return $this->object;
    }
}
