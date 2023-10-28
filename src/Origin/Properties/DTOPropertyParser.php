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

namespace srag\Plugins\Hub2\Origin\Properties;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class DTOPropertyParser
 * @package srag\Plugins\Hub2\Origin\Properties
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class DTOPropertyParser
{
    /**
     * @var string
     */
    private string $dtoClass;

    /**
     * @param string $dtoClass Fully qualified name of a DTO class, e.g. UserDTO
     */
    public function __construct(string $dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    /**
     * @return DTOProperty[]
     */
    public function getProperties(): array
    {
        $reflection = new ReflectionClass($this->dtoClass);
        $reflectionProperties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED);
        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            // Look for a @description php doc block
            $out = [];
            preg_match('/@description\s(\w+)/', $reflectionProperty->getDocComment(), $out);
            $descriptionKey = count($out) ? $out[1] : '';
            $properties[] = new DTOProperty($reflectionProperty->name, $descriptionKey);
        }

        return $properties;
    }
}
