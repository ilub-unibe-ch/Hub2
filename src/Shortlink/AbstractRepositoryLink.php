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

use ilLink;
use ilObject2;

/**
 * Class AbstractRepositoryLink
 * @package srag\Plugins\Hub2\Shortlink
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
abstract class AbstractRepositoryLink extends AbstractBaseLink implements IObjectLink
{
    /**
     * @inheritdoc
     */
    public function doesObjectExist(): bool
    {
        if (!$this->getILIASId()) {
            return false;
        }

        return ilObject2::_exists((int)$this->getILIASId(), true);
    }

    /**
     * @inheritdoc
     */
    public function isAccessGranted(): bool
    {
        return $this->access->checkAccess("read", '', (int)$this->getILIASId());
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedInternalLink(): string
    {
        if ($this->isAccessGranted()) {
            return $this->getAccessGrantedExternalLink();
        } else {
            return $this->getAccessDeniedLink();
        }
    }

    /**
     * @inheritdoc
     */
    public function getAccessGrantedExternalLink(): string
    {
        $ref_id = $this->getILIASId();
        return $this->generateLink((int)$ref_id);
    }

    /**
     * @inheritdoc
     */
    public function getAccessDeniedLink(): string
    {
        $ref_id = $this->findReadableParent();
        if ($ref_id === 0) {
            return "index.php";
        }

        return $this->generateLink($ref_id);
    }

    /**
     * @return int
     */
    protected function getILIASId(): string
    {
        return $this->object->getILIASId();
    }

    /**
     * @return int
     */
    private function findReadableParent(): int
    {
        $ref_id = (int)$this->getILIASId();

        while ($ref_id and !$this->access->checkAccess('read', '', $ref_id) and $ref_id != 1) {
            $ref_id = (int) $this->tree->getParentId($ref_id);
        }

        if (!$ref_id || $ref_id === 1) {
            if (!$this->access->checkAccess('read', '', $ref_id)) {
                return 0;
            }
        }

        return $ref_id;
    }

    /**
     * @param int $ref_id
     * @return array|string|string[]
     */
    private function generateLink(int $ref_id)
    {
        $link = ilLink::_getLink($ref_id);
        return str_replace(ILIAS_HTTP_PATH, "", $link);
    }
}
