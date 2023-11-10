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

namespace srag\Plugins\Hub2\Log;

use ilDateTime;
use stdClass;

/**
 * Interface ILog
 * @package srag\Plugins\Hub2\Log
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface ILog
{
    /**
     * @var int
     */
    public const LEVEL_INFO = 200;
    /**
     * @var int
     */
    public const LEVEL_WARNING = 300;
    /**
     * @var int
     */
    public const LEVEL_EXCEPTION = 400;
    /**
     * @var int
     */
    public const LEVEL_CRITICAL = 500;

    public function getLogId(): ?int;

    public function withLogId(int $log_id): self;

    public function getTitle(): string;

    public function withTitle(string $title): self;

    public function getMessage(): string;

    public function withMessage(string $message): self;

    public function getStatus(): int;

    /**
     * @return $this
     */
    public function withStatus(int $status): self;

    public function getDate(): ilDateTime;

    public function withDate(ilDateTime $date): self;

    public function getLevel(): int;

    public function withLevel(int $level): self;

    public function getAdditionalData(): stdClass;

    public function withAdditionalData(stdClass $additional_data): self;

    /**
     * @param mixed $value
     */
    public function addAdditionalData(string $key, $value): self;

    public function getOriginId(): ?int;

    public function withOriginId(int $origin_id): self;

    public function getOriginObjectType(): string;

    public function withOriginObjectType(string $origin_object_type): self;

    public function getObjectExtId(): ?string;

    /**
     * @param string|null $object_ext_id
     */
    public function withObjectExtId(
        string $object_ext_id = null
    ): self;

    /**
     * @return int|null
     */
    public function getObjectIliasId(): ?int;

    /**
     * @param int|null $object_ilias_id
     */
    public function withObjectIliasId(int $object_ilias_id = null): self;

    /**
     * Syntactic sugar for self::logs()->storeLog($log->withMessage()->withLevel());
     */
    public function write(string $message, int $level = self::LEVEL_INFO): void;
}
