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
use ilUtil;

use srag\Plugins\Hub2\Config\ArConfig;
use srag\Plugins\Hub2\Exception\HubException;


/**
 * Class OriginImplementationTemplateGenerator
 * @package srag\Plugins\Hub2\Origin
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class OriginImplementationTemplateGenerator
{
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;

    /**
     * OriginImplementationTemplateGenerator constructor
     */
    public function __construct()
    {

    }

    /**
     * Create the implementation class file from a given template at the correct location
     * based on the hub config.
     * @param IOrigin $origin
     * @return bool False if file exists, true if created
     * @throws HubException
     */
    public function create(IOrigin $origin): bool
    {
        $classFile = $this->getClassFilePath($origin);
        if ($this->classFileExists($origin)) {
            return false;
        }
        $path = $this->getPath($origin);
        if (!is_dir($path)) {
            if (!ilUtil::makeDirParents($path)) {
                throw new HubException("Could not create directory: $path");
            };
        }
        $template = file_get_contents(__DIR__ . '/../../templates/OriginImplementationTemplate.tpl');
        if ($template === false) {
            throw new HubException("Could not load template: $template");
        }
        $className = $origin->getImplementationClassName();
        $namespace = $origin->getImplementationNamespace();
        $content = str_replace('[[CLASSNAME]]', $className, $template);
        $content = str_replace('[[NAMESPACE]]', $namespace, $content);
        $result = file_put_contents($classFile, $content);
        if ($result === false) {
            throw new HubException("Unable to create template for origin implementation");
        }

        return true;
    }

    public function classFileExists(IOrigin $origin): bool
    {
        $classFile = $this->getClassFilePath($origin);

        return is_file($classFile);
    }

    /**
     * @param IOrigin $origin
     * @return string
     */
    public function getClassFilePath(IOrigin $origin): string
    {
        $path = $this->getPath($origin);
        $className = $origin->getImplementationClassName();
        return $path . $className . '.php';
    }

    /**
     * @param IOrigin $origin
     * @return string
     */
    protected function getPath(IOrigin $origin): string
    {
        $basePath = rtrim(ArConfig::getField(ArConfig::KEY_ORIGIN_IMPLEMENTATION_PATH), '/') . '/';
        return $basePath . $origin->getObjectType() . '/';
    }
}
