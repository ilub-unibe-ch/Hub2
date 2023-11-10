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

namespace srag\Plugins\Hub2\Origin;

use ilHub2Plugin;

use srag\Plugins\Hub2\Log\ILog;
use srag\Plugins\Hub2\MappingStrategy\IMappingStrategyFactory;
use srag\Plugins\Hub2\Metadata\IMetadataFactory;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\IDataTransferObjectFactory;
use srag\Plugins\Hub2\Object\HookObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Taxonomy\ITaxonomyFactory;
use srag\Plugins\Hub2\Logs\Logs;

/**
 * Class AbstractOriginImplementation
 * Any implementation of a origin MUST extend this class.
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractOriginImplementation implements IOriginImplementation
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IMappingStrategyFactory
     */
    private IMappingStrategyFactory $mapping_strategy_factory;
    /**
     * @var ITaxonomyFactory
     */
    private ITaxonomyFactory $taxonomyFactory;
    /**
     * @var IMetadataFactory
     */
    private IMetadataFactory $metadataFactory;
    /**
     * @var IOriginConfig
     */
    private IOriginConfig $originConfig;
    /**
     * @var IDataTransferObjectFactory
     */
    private IDataTransferObjectFactory $factory;
    /**
     * @var array
     */
    protected array $data = [];
    /**
     * @var IOrigin
     */
    protected IOrigin $origin;

    /**
     * AbstractOriginImplementation constructor
     * @param IOriginConfig              $config
     * @param IDataTransferObjectFactory $factory
     * @param IMetadataFactory           $metadataFactory
     * @param ITaxonomyFactory           $taxonomyFactory
     * @param IMappingStrategyFactory    $mapping_strategy
     * @param IOrigin                    $origin
     */
    public function __construct(
        IOriginConfig $config,
        IDataTransferObjectFactory $factory,
        IMetadataFactory $metadataFactory,
        ITaxonomyFactory $taxonomyFactory,
        IMappingStrategyFactory $mapping_strategy,
        IOrigin $origin
    ) {
        $this->originConfig = $config;
        $this->factory = $factory;
        $this->metadataFactory = $metadataFactory;
        $this->taxonomyFactory = $taxonomyFactory;
        $this->mapping_strategy_factory = $mapping_strategy;
        $this->origin = $origin;
    }

    /**
     * @return IOriginConfig
     */
    final protected function config(): IOriginConfig
    {
        return $this->originConfig;
    }

    /**
     * @return IDataTransferObjectFactory
     */
    final protected function factory(): IDataTransferObjectFactory
    {
        return $this->factory;
    }

    /**
     * @param IDataTransferObject $dto
     * @return ILog
     */
    protected function log(IDataTransferObject $dto = null): ILog
    {
        return Logs::getInstance()->originLog($this->origin, null, $dto);
    }

    /**
     * @return IMappingStrategyFactory
     */
    final protected function mapping(): IMappingStrategyFactory
    {
        return $this->mapping_strategy_factory;
    }

    /**
     * @return IMetadataFactory
     */
    final protected function metadata(): IMetadataFactory
    {
        return $this->metadataFactory;
    }

    /**
     * @return ITaxonomyFactory
     */
    final protected function taxonomy(): ITaxonomyFactory
    {
        return $this->taxonomyFactory;
    }

    // HOOKS

    /**
     * @inheritdoc
     */
    public function overrideStatus(HookObject $hook)
    {
        // TODO: Implement overrideStatus() method.
    }

    /**
     * @inheritdoc
     */
    public function getAdHocParentScopesAsExtIds(): array
    {
        return [];
    }
}
