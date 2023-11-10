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

use srag\Plugins\Hub2\Origin\Config\IOriginConfig;
use srag\Plugins\Hub2\Origin\Properties\IOriginProperties;

/**
 * Interface Origin
 * @package srag\Plugins\Hub2\Origin
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IOrigin
{
    public const OBJECT_TYPE_USER = 'user';
    public const OBJECT_TYPE_COURSE_MEMBERSHIP = 'courseMembership';
    public const OBJECT_TYPE_COURSE = 'course';
    public const OBJECT_TYPE_CATEGORY = 'category';
    public const OBJECT_TYPE_GROUP = 'group';
    public const OBJECT_TYPE_GROUP_MEMBERSHIP = 'groupMembership';
    public const OBJECT_TYPE_SESSION = 'session';
    public const OBJECT_TYPE_SESSION_MEMBERSHIP = 'sessionMembership';
    public const OBJECT_TYPE_ORGNUNIT = "orgUnit";
    public const OBJECT_TYPE_ORGNUNIT_MEMBERSHIP = "orgUnitMembership";
    public const ORIGIN_MAIN_NAMESPACE = "srag\\Plugins\\Hub2\\Origin";

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): IOrigin;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): IOrigin;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): IOrigin;

    /**
     * @return string
     */
    public function getImplementationClassName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setImplementationClassName(string $name): IOrigin;

    /**
     * @return string
     */
    public function getImplementationNamespace(): string;

    /**
     * @param string $implementation_namespace
     */
    public function setImplementationNamespace(string $implementation_namespace);

    /**
     * Get the object type that will be synced with this origin, e.g.
     * user|course|category|courseMembership
     * @return string
     */
    public function getObjectType(): string;

    /**
     * @param string $type
     * @return $this
     */
    public function setObjectType(string $type): IOrigin;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Get access to all configuration data of this origin.
     * @return IOriginConfig
     */
    public function config(): IOriginConfig;

    /**
     * Get access to all properties data of this origin.
     * @return IOriginProperties
     */
    public function properties(): IOriginProperties;

    /**
     * @return string
     */
    public function getLastRun(): ?string;

    /**
     * @param string $last_run
     */
    public function setLastRun(string $last_run);

    /**
     *
     */
    public function update();

    /**
     *
     */
    public function create();

    /**
     * Run Sync without Hash comparison
     */
    public function forceUpdate();

    /**
     * @return bool
     */
    public function isUpdateForced(): bool;

    /**
     * @return bool
     */
    public function isAdHoc(): bool;

    /**
     * @param bool $active
     */
    public function setAdHoc(bool $adhoc)/*: void*/
    ;

    /**
     * @return bool
     */
    public function isAdhocParentScope(): bool;

    /**
     * @param bool $adhoc_parent_scope
     */
    public function setAdhocParentScope(bool $adhoc_parent_scope)/*: void*/
    ;

    /**
     * @return int
     */
    public function getSort(): int;

    /**
     * @param int $sort
     */
    public function setSort(int $sort)/*: void*/
    ;

    /**
     *
     */
    public function store();
}
