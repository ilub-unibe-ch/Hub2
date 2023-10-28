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

namespace srag\Plugins\Hub2\Exception;

/**
 * Class BuildObjectsFailedException
 * This exception is thrown if an unkown language code is passed to some dto
 * @package srag\Plugins\Hub2\Exception
 * @author  Timon Amstutz
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class LanguageCodeException extends HubException
{

    /**
     * LanguageCodeException constructor
     * @param string $code
     */
    public function __construct($code = "")
    {
        parent::__construct("Language Code does not exist, ID: '$code'");
    }
}
