<?php

/*
 *  Project:    sfw2-boilerplate
 *
 *  Copyright (C) 2022 Stefan Paproth <pappi-@gmx.de>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */

declare(strict_types=1);

namespace SFW2\Session\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SFW2\Core\HttpExceptions\HttpUnprocessableContent;
use SFW2\Session\XSRFToken;

final class XSRFTokenHandler implements MiddlewareInterface
{
    public function __construct(private readonly XSRFToken $xsrfToken)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws Exception|InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) != 'POST') {
            return $this->handleAndRespose($request, $handler);
        }

        $headers = $request->getHeader('X-CSRF-Token');
        $token = (string)array_pop($headers);

        if (!$this->xsrfToken->compareToken($token)) {
            throw new HttpUnprocessableContent("invalid xsrf-token given");
        }
        return $this->handleAndRespose($request, $handler);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function handleAndRespose(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $newToken = $this->xsrfToken->generateToken();
        $request->withAttribute('sfw2_session', ['xsrf_token' => $newToken]);

        return $handler->handle($request)->withHeader('X-CSRF-Token', $newToken);
    }
}
