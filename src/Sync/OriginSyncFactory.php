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

use srag\Plugins\Hub2\Exception\HubException;
use srag\Plugins\Hub2\Object\IObjectRepository;
use srag\Plugins\Hub2\Object\ObjectFactory;
use srag\Plugins\Hub2\Origin\Config\OriginImplementationFactory;
use srag\Plugins\Hub2\Origin\IOrigin;
use srag\Plugins\Hub2\Origin\IOriginImplementation;
use srag\Plugins\Hub2\Sync\Processor\IObjectSyncProcessor;
use srag\Plugins\Hub2\Sync\Processor\SyncProcessorFactory;


/**
 * Class OriginSyncFactory
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginSyncFactory
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOrigin
     */
    protected IOrigin $origin;

    /**
     * @param IOrigin $origin
     */
    public function __construct(IOrigin $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return OriginSync
     * @throws HubException
     */
    public function instance(): OriginSync
    {
        $statusTransition = new ObjectStatusTransition($this->origin->config());

        return new OriginSync(
            $this->origin,
            $this->getObjectRepository(),
            new ObjectFactory($this->origin),
            $statusTransition
        );
    }

    /**
     * @param OriginSync $originSync
     * @throws HubException
     */
    public function initImplementation(OriginSync $originSync)
    {
        $implementationFactory = new OriginImplementationFactory($this->origin);

        $originImplementation = $implementationFactory->instance();

        $originSync->setProcessor($this->getSyncProcessor(
            $this->origin,
            $originImplementation,
            $originSync->getStatusTransition()
        ));

        $originSync->setImplementation($originImplementation);
    }

    /**
     * @return IObjectRepository
     */
    protected function getObjectRepository(): IObjectRepository
    {
        $ucfirst = ucfirst($this->origin->getObjectType());
        $class = "srag\\Plugins\\Hub2\\Object\\{$ucfirst}\\{$ucfirst}Repository";

        return new $class($this->origin);
    }

    /**
     * @param IOrigin                 $origin
     * @param IOriginImplementation   $implementation
     * @param IObjectStatusTransition $statusTransition
     * @return IObjectSyncProcessor
     */
    protected function getSyncProcessor(
        IOrigin $origin,
        IOriginImplementation $implementation,
        IObjectStatusTransition $statusTransition
    ): IObjectSyncProcessor {
        $processorFactory = new SyncProcessorFactory($origin, $implementation, $statusTransition);
        $processor = $origin->getObjectType();

        return $processorFactory->$processor();
    }
}
