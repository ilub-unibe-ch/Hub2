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

use DateTime;

/**
 * Describes common properties among the different hub object types
 * @package srag\Plugins\Hub2\Object
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
interface IObject
{
    /**
     * Initial status indicating that this object is new and an ILIAS object needs to be created by
     * the sync.
     */
    public const STATUS_NEW = 1;
    /**
     * Intermediate status indicating that a corresponding ILIAS object must be created.
     */
    public const STATUS_TO_CREATE = 2;
    /**
     * Final status indicating that the corresponding ILIAS object has been created.
     */
    public const STATUS_CREATED = 4;
    /**
     * Intermediate status indicating that the corresponding ILIAS object must be updated.
     */
    public const STATUS_TO_UPDATE = 8;
    /**
     * Final status indicating that the corresponding ILIAS object has been updated.
     */
    public const STATUS_UPDATED = 16;
    /**
     * Intermediate status indicating that the corresponding ILIAS object must be deleted.
     */
    public const STATUS_TO_OUTDATED = 32;
    /**
     * Final status indicating that the corresponding ILIAS object has been deleted.
     */
    public const STATUS_OUTDATED = 64;
    /**
     * Intermediate status indicating that the object was deleted an has now been delivered again.
     */
    public const STATUS_TO_RESTORE = 128;
    /**
     * Something on ILIAS side by processing the object goes wrong
     */
    public const STATUS_FAILED = 256;
    /**
     * Final status indicating that the object is ignored and not processed by the sync,
     * e.g. the period of the object does not match the actual period defined by the origin.
     */
    public const STATUS_IGNORED = 4096;

    /**
     * Get a unique ID of this object.
     * @return mixed
     */
    public function getId();

    /**
     * Get the external ID of this object. This ID serves as primary key to identify an object
     * inside an origin.
     * @return string
     */
    public function getExtId(): string;

    /**
     * Set the external ID of this object. This ID serves as primary key to identify an object.
     * @param string $id
     * @return $this
     */
    public function setExtId(string $id): IObject;

    /**
     * Get the date where the data of this object was delivered from the external system, e.g. via
     * CSV.
     * @return DateTime
     */
    public function getDeliveryDate(): DateTime;

    /**
     * @param int $unix_timestamp
     * @return $this
     */
    public function setDeliveryDate(int $unix_timestamp): IObject;

    /**
     * Get the date where the sync processed this object, e.g. to create/update the corresponding
     * ILIAS object depending on the status.
     * @return DateTime
     */
    public function getProcessedDate(): DateTime;

    /**
     * @param int $unix_timestamp
     * @return $this
     */
    public function setProcessedDate(int $unix_timestamp): IObject;

    /**
     * Get the ID of this object in ILIAS. Depending on the object, this can either be the ILIAS
     * object-ID or ref-ID.
     * @return string
     */
    public function getILIASId(): string;

    /**
     * @param int|string $id
     * @return $this
     */
    public function setILIASId(string $id): IObject;

    /**
     * Get the status of this object.
     * @return int
     */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): IObject;

    /**
     * Get the period (aka semester) where this object belongs to. The origin sync only processes
     * this object if the current period equals the period returned here.
     * Return an empty string if this object is active for any period.
     * @return string
     */
    public function getPeriod(): string;

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod(string $period): IObject;

    /**
     * Compute a hashcode of this object hashing all relevant properties.
     * This hashcode is for example used when processing the ILIAS object during a sync. If the
     * current hashcode is identical to the one in the database, no properties of the object were
     * changed. This means that the sync can skip processing the ILIAS object.
     * Note: Different objects MAY have identical hashcodes.
     * @return string
     */
    public function computeHashCode(): string;

    /**
     * Get the current hash code of this object, e.g. the hash stored in db. May not be up to date!
     * Use computeHashCode() to get the actual hashcode.
     * @return string
     */
    public function getHashCode(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * Set properties from an associative array.
     * @param array $data
     * @return $this
     */
    public function setData(array $data): IObject;

    /**
     * Persist data in database.
     */
    public function store();
}
