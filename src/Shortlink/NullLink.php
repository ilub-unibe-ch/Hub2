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

namespace srag\Plugins\Hub2\Shortlink;

/**
 * Class NullLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class NullLink implements IObjectLink
{
    /**
     * @inheritdoc
     */
    public function doesObjectExist(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isAccessGranted(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedExternalLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getAccessDeniedLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getNonExistingLink(): string
    {
        return "index.php";
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedInternalLink(): string
    {
        return "index.php";
    }
}
