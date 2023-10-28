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

namespace srag\Plugins\Hub2\Object\DTO;

use Serializable;

/**
 * Data Transfer Objects holding all data of objects in the hub context, e.g.
 * Users, Courses, CourseMemberships...
 * @package srag\Plugins\Hub2\Object\DTO
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IDataTransferObject
{
    /**
     * Get the external ID of this object. This ID serves as primary key to identify an object of a
     * given object type.
     * @return string
     */
    public function getExtId(): string;

    /**
     * Get the period (aka semester) where this object belongs to. The origin sync only processes
     * this object if this period equals to the period defined by the origin.
     * Return an empty string if this object is active for any period.
     * @return string
     */
    public function getPeriod(): string;

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod(string $period): IDataTransferObject;

    /**
     * Get all data as associative array
     * @return array
     */
    public function getData(): array;

    /**
     * Set all data as associative array
     * @param array $data
     * @return $this
     */
    public function setData(array $data): IDataTransferObject;

    /**
     * @return bool
     */
    public function shouldDeleted(): bool;

    /**
     * @param bool $should_deleted
     * @return static
     */
    public function setShouldDeleted(bool $should_deleted): IDataTransferObject;

    /**
     * Get the additional data stored on the dto persistently
     * @return Serializable
     */
    public function getAdditionalData(): Serializable;

    /**
     * Add some additional data to store persistently in the DB along with the data of the
     * dto.
     * @param Serializable $additionalData
     * @return mixed
     */
    public function withAdditionalData(Serializable $additionalData);
}
