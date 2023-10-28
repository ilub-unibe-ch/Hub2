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

namespace srag\Plugins\Hub2\Sync;

use ilHub2Plugin;

use srag\Plugins\Hub2\Object\IObject;
use srag\Plugins\Hub2\Origin\Config\IOriginConfig;


/**
 * Class ObjectStatusTransition
 * @package srag\Plugins\Hub2\Sync
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @deprecated
 */
class ObjectStatusTransition implements IObjectStatusTransition
{
    /**
     * @var string
     * @deprecated
     */
    public const PLUGIN_CLASS_NAME = ilHub2Plugin::class;
    /**
     * @var IOriginConfig
     * @deprecated
     */
    protected IOriginConfig $config;

    /**
     * @param IOriginConfig $config
     * @deprecated
     */
    public function __construct(IOriginConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     * @deprecated
     */
    public function finalToIntermediate(IObject $object): int
    {
        // If the config has defined an active period and the period of the object does not match,
        // we set the status to IGNORED. The sync won't process this object anymore.
        // If at any time there is no active period defined OR the object matches the period again,
        // the status will be set to TO_UPDATE or TO_CREATE again.
        $active_period = $this->config->getActivePeriod();
        if ($active_period && ($object->getPeriod() != $active_period)) {
            return IObject::STATUS_IGNORED;
        }

        switch ($object->getStatus()) {
            case IObject::STATUS_NEW:
                return IObject::STATUS_TO_CREATE;

            case IObject::STATUS_CREATED:
            case IObject::STATUS_UPDATED:
                return IObject::STATUS_TO_UPDATE;

            case IObject::STATUS_TO_OUTDATED:
            case IObject::STATUS_OUTDATED:
                return IObject::STATUS_TO_RESTORE;

            case IObject::STATUS_IGNORED:
            case IObject::STATUS_FAILED:
                // Either create or update the ILIAS object
                return ((int)$object->getILIASId()) ? IObject::STATUS_TO_UPDATE : IObject::STATUS_TO_CREATE;

            default:
                return $object->getStatus();
        }
    }
}
