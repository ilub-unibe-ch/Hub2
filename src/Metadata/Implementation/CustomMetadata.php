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

namespace srag\Plugins\Hub2\Metadata\Implementation;

use ilADTDate;
use ilADTExternalLink;
use ilADTInternalLink;
use ilADTText;
use ilADTLocalizedText;
use ilAdvancedMDValues;
use ilDateTime;
use ilADTLocation;

/**
 * Class CustomMetadata
 * @package srag\Plugins\Hub2\Metadata\Implementation
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class CustomMetadata extends AbstractImplementation implements IMetadataImplementation
{
    /**
     * @inheritdoc
     */
    public function write(): void
    {
        $id = $this->getMetadata()->getIdentifier();

        $ilAdvancedMDValues = new ilAdvancedMDValues(
            $this->getMetadata()->getRecordId(),
            $this->getIliasId(),
            null,
            '-'
        );

        $ilAdvancedMDValues->read();
        $ilADTGroup = $ilAdvancedMDValues->getADTGroup();
        $value = $this->getMetadata()->getValue();
        $ilADT = $ilADTGroup->getElement($id);

        switch (true) {
            case $ilADT instanceof ilADTLocalizedText:
                $ilADT->setTranslation('de', $value);
                break;
            case $ilADT instanceof ilADTText:
                $ilADT->setText($value);
                break;
            case $ilADT instanceof ilADTDate:
                $ilADT->setDate(new ilDateTime(time(), IL_CAL_UNIX));
                break;
            case $ilADT instanceof ilADTExternalLink:
                $ilADT->setUrl($value['url']);
                $ilADT->setTitle($value['title']);
                break;
            case $ilADT instanceof ilADTInternalLink:
                $ilADT->setTargetRefId($value);
                break;
            case $ilADT instanceof ilADTLocation:

                $clearLocation = static function (ilADTLocation $adt): void {
                    $adt->setLatitude();
                    $adt->setLongitude();
                    $adt->setZoom(0);
                };

                if (!is_array($value)) {
                    $clearLocation($ilADT);
                    break;
                }

                $lat_raw = $value['latitude'] ?? null;
                $lon_raw = $value['longitude'] ?? null;

                if (
                    $lat_raw === null || $lon_raw === null ||
                    $lat_raw === '' || $lon_raw === '' ||
                    !is_numeric($lat_raw) || !is_numeric($lon_raw)
                ) {
                    $clearLocation($ilADT);
                    break;
                }

                $lat = (float) $lat_raw;
                $lon = (float) $lon_raw;

                if ($lat < -90.0 || $lat > 90.0 || $lon < -180.0 || $lon > 180.0) {
                    $clearLocation($ilADT);
                    break;
                }

                $ilADT->setLatitude($lat);
                $ilADT->setLongitude($lon);

                $zoom_raw = $value['zoom'] ?? null;
                if ($zoom_raw === null || $zoom_raw === '' || !is_numeric($zoom_raw)) {
                    $ilADT->setZoom(17);
                } else {
                    $ilADT->setZoom((int) $zoom_raw);
                }
                break;
        }

        $ilAdvancedMDValues->write();
    }

    /**
     * @inheritdoc
     */
    public function read(): void
    {
        // no need for a read-Method since wo have to update them anyways due to performance-issues when reading all metadata everytime
    }
}
