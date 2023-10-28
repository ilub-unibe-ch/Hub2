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

namespace srag\Plugins\Hub2\Sync\Processor;

use ilContainer;
use ilObject;
use ilObjectServiceSettingsGUI;
use srag\Plugins\Hub2\Object\DTO\ITaxonomyAwareDataTransferObject;
use srag\Plugins\Hub2\Taxonomy\Implementation\TaxonomyImplementationFactory;

/**
 * Class TaxonomySyncProcessor
 * @package srag\Plugins\Hub2\Sync\Processor
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
trait TaxonomySyncProcessor
{

    /**
     * @param ITaxonomyAwareDataTransferObject $dto
     * @param ilObject                         $object
     */
    public function handleTaxonomies(ITaxonomyAwareDataTransferObject $dto, ilObject $object)
    {
        if (count($dto->getTaxonomies()) > 0) {
            ilContainer::_writeContainerSetting($object->getId(), ilObjectServiceSettingsGUI::TAXONOMIES, 1);

            $f = new TaxonomyImplementationFactory();
            foreach ($dto->getTaxonomies() as $taxonomy) {
                $f->taxonomy($taxonomy, $object)->write();
            }
        }
    }
}
