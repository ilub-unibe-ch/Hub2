<?php

namespace SRAG\Plugins\Hub2\Metadata;

use ILIAS\UI\NotImplementedException;

/**
 * Class IMetadataFactory
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class MetadataFactory implements IMetadataFactory {

	/**
	 * @param int $id
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithIliasId(int $ilas_id, int $record_id = 1): IMetadata {
		return new Metadata($ilas_id,$record_id);
	}


	/**
	 * @param string $title
	 *
	 * @return \SRAG\Plugins\Hub2\Metadata\IMetadata
	 */
	public function getDTOWithFirstIliasIdForTitle(string $title): IMetadata {
		$ilAdvancedMDValues = new \ilAdvancedMDValues(1, $this->getIliasId(), null, "-");

		throw new NotImplementedException('not yet implemented');
	}
}
