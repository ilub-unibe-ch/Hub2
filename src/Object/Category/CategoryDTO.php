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

namespace srag\Plugins\Hub2\Object\Category;

use InvalidArgumentException;
use srag\Plugins\Hub2\MappingStrategy\MappingStrategyAwareDataTransferObject;
use srag\Plugins\Hub2\Object\DTO\DataTransferObject;
use srag\Plugins\Hub2\Object\DTO\TaxonomyAndMetadataAwareDataTransferObject;

/**
 * Class CategoryDTO
 * @package srag\Plugins\Hub2\Object\Category
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CategoryDTO extends DataTransferObject implements ICategoryDTO
{
    use TaxonomyAndMetadataAwareDataTransferObject;
    use MappingStrategyAwareDataTransferObject;

    private static array $orderTypes = [
        self::ORDER_TYPE_TITLE,
        self::ORDER_TYPE_MANUAL,
        self::ORDER_TYPE_ACTIVATION,
        self::ORDER_TYPE_INHERIT,
        self::ORDER_TYPE_CREATION,
    ];
    private static array $parentIdTypes = [
        self::PARENT_ID_TYPE_REF_ID,
        self::PARENT_ID_TYPE_EXTERNAL_EXT_ID,
    ];
    protected string $title;
    protected ?string $description = "";
    protected int $orderType = self::ORDER_TYPE_TITLE;
    protected int $owner = 6;
    protected string $parentId;
    protected int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    protected bool $showNews = true;
    protected bool $showInfoPage = true;

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): CategoryDTO
    {
        $this->title = $title;

        return $this;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): CategoryDTO
    {
        $this->description = $description;

        return $this;
    }

    public function getOrderType(): int
    {
        return $this->orderType;
    }

    public function setOrderType(int $orderType): CategoryDTO
    {
        if (!in_array($orderType, self::$orderTypes)) {
            throw new InvalidArgumentException("Given '$orderType' is not a valid order type'");
        }
        $this->orderType = $orderType;

        return $this;
    }
    public function getOwner(): int
    {
        return $this->owner;
    }
    public function setOwner(int $owner): CategoryDTO
    {
        $this->owner = $owner;

        return $this;
    }

    public function getParentId(): string
    {
        return $this->parentId;
    }

    public function setParentId(string $parentId): CategoryDTO
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getParentIdType(): int
    {
        return $this->parentIdType;
    }


    public function setParentIdType(int $parentIdType): CategoryDTO
    {
        if (!in_array($parentIdType, self::$parentIdTypes)) {
            throw new InvalidArgumentException("Invalid parentIdType given '$parentIdType'");
        }
        $this->parentIdType = $parentIdType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowNews(): bool
    {
        return $this->showNews;
    }

    /**
     * @param bool $showNews
     * @return CategoryDTO
     */
    public function setShowNews(bool $showNews): CategoryDTO
    {
        $this->showNews = $showNews;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowInfoPage(): bool
    {
        return $this->showInfoPage;
    }

    /**
     * @param bool $showInfoPage
     * @return CategoryDTO
     */
    public function setShowInfoPage(bool $showInfoPage): CategoryDTO
    {
        $this->showInfoPage = $showInfoPage;

        return $this;
    }
}
