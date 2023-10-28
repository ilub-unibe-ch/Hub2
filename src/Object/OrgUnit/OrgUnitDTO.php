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

namespace srag\Plugins\Hub2\Object\OrgUnit;

use srag\Plugins\Hub2\Object\DTO\DataTransferObject;

/**
 * Class OrgUnitDTO
 * @package srag\Plugins\Hub2\Object\OrgUnit
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitDTO extends DataTransferObject implements IOrgUnitDTO
{
    /**
     * @var string
     */
    protected string $title = "";
    /**
     * @var string
     */
    protected string $description = "";
    /**
     * @var int
     */
    protected int $owner = 6;
    /**
     * @var int|string|null
     */
    protected $parent_id = null;
    /**
     * @var int
     */
    protected int $parent_id_type = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var string
     */
    protected string $org_unit_type = "";
    /**
     * @var string
     */
    protected string $ext_id = "";

    /**
     * @inheritdoc
     */
    public function __construct(string $ext_id)
    {
        parent::__construct($ext_id);
        $this->ext_id = $ext_id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): IOrgUnitDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description): IOrgUnitDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @inheritdoc
     */
    public function setOwner(int $owner): IOrgUnitDTO
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @inheritdoc
     */
    public function setParentId(int|string|null $parent_id): IOrgUnitDTO
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParentIdType(): int
    {
        return $this->parent_id_type;
    }

    /**
     * @inheritdoc
     */
    public function setParentIdType(int $parent__Id__type): IOrgUnitDTO
    {
        $this->parent_id_type = $parent__Id__type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrgUnitType(): string
    {
        return $this->org_unit_type;
    }

    /**
     * @inheritdoc
     */
    public function setOrgUnitType(string $org_unit_type): IOrgUnitDTO
    {
        $this->org_unit_type = $org_unit_type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtId(): string
    {
        return $this->ext_id;
    }

    /**
     * @inheritdoc
     */
    public function setExtId(string $ext_id): IOrgUnitDTO
    {
        $this->ext_id = $ext_id;

        return $this;
    }
}
