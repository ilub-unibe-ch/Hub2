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

namespace srag\Plugins\Hub2\FileDrop;

use Psr\Http\Message\RequestInterface;

/**
 * Class Token
 * @author Fabian Schmid <fabian@sr.solutions>
 */
class Token
{
    private const LENGTH = 16;
    private const BEARER = 'Bearer';
    private const SPACE = ' ';
    private const HEADER_AUTHORIZATION = 'Authorization';

    public function generate(): string
    {
        try {
            $token = bin2hex(random_bytes(self::LENGTH));
        } catch (\Throwable $t) {
            $token = hash('sha256', uniqid((string) time(), true));
        }

        return str_replace(self::SPACE, '', substr($token, 0, self::LENGTH));
    }

    public function fromRequest(RequestInterface $request): ?string
    {
        $token = $request->getHeaderLine(self::HEADER_AUTHORIZATION);
        if (empty($token)) {
            return null;
        }
        $slit_token = explode(self::SPACE, $token);
        if ($slit_token[0] !== self::BEARER || !isset($slit_token[1]) || $slit_token[1] === '') {
            return null;
        }

        return $slit_token[1] ?? null;
    }

}
