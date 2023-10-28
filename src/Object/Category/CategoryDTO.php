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

    /**
     * @var array
     */
    private static array $orderTypes = [
        self::ORDER_TYPE_TITLE,
        self::ORDER_TYPE_MANUAL,
        self::ORDER_TYPE_ACTIVATION,
        self::ORDER_TYPE_INHERIT,
        self::ORDER_TYPE_CREATION,
    ];
    /**
     * @var array
     */
    private static array $parentIdTypes = [
        self::PARENT_ID_TYPE_REF_ID,
        self::PARENT_ID_TYPE_EXTERNAL_EXT_ID,
    ];
    /**
     * @var string
     */
    protected string $title;
    /**
     * @var string
     */
    protected string $description;
    /**
     * @var int
     */
    protected int $orderType = self::ORDER_TYPE_TITLE;
    /**
     * @var int
     */
    protected int $owner = 6;
    /**
     * @var int
     */
    protected int $parentId;
    /**
     * @var int
     */
    protected int $parentIdType = self::PARENT_ID_TYPE_REF_ID;
    /**
     * @var bool
     */
    protected bool $showNews = true;
    /**
     * @var bool
     */
    protected bool $showInfoPage = true;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CategoryDTO
     */
    public function setTitle(string $title): CategoryDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CategoryDTO
     */
    public function setDescription(string $description): CategoryDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderType(): int
    {
        return $this->orderType;
    }

    /**
     * @param int $orderType
     * @return CategoryDTO
     */
    public function setOrderType(int $orderType): CategoryDTO
    {
        if (!in_array($orderType, self::$orderTypes)) {
            throw new InvalidArgumentException("Given '$orderType' is not a valid order type'");
        }
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     * @return CategoryDTO
     */
    public function setOwner(int $owner): CategoryDTO
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     * @return $this
     */
    public function setParentId(int $parentId): CategoryDTO
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return int
     */
    public function getParentIdType(): int
    {
        return $this->parentIdType;
    }

    /**
     * @param int $parentIdType
     * @return CategoryDTO
     */
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
