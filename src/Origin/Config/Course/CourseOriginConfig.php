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

namespace srag\Plugins\Hub2\Origin\Config\Course;

use srag\Plugins\Hub2\Origin\Config\OriginConfig;

/**
 * Class CourseOriginConfig
 * @package srag\Plugins\Hub2\Origin\Config\Course
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CourseOriginConfig extends OriginConfig implements ICourseOriginConfig
{
    /**
     * @var array
     */
    protected array $courseData = [
        self::REF_ID_NO_PARENT_ID_FOUND => 1,
    ];

    public function __construct(array $data)
    {
        parent::__construct(array_merge($this->courseData, $data));
    }

    /**
     * @inheritdoc
     */
    public function getParentRefIdIfNoParentIdFound(): int
    {
        return intval($this->get(self::REF_ID_NO_PARENT_ID_FOUND));
    }
}
