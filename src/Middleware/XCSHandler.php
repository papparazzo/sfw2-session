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
use SFW2\Session\XSRFToken;

class XCSHandler implements MiddlewareInterface {

    private XSRFToken $xsrfToken;

    public function __construct(XSRFToken $xsrfToken) {
        $this->xsrfToken = $xsrfToken;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {

        if(strtoupper($request->getMethod()) != 'POST') {
            return $handler->handle($request)->withHeader('X-CSRF-Token', $this->xsrfToken->generateToken());
        }

        $token = (string)$request->getAttribute(XSRFToken::XSS_TOKEN);

        if(!$this->xsrfToken->compareToken($token)) {


            #403 (Forbidden)
            #return $handler->handle($request)->withHeader('X-CSRF-Token', $this->xsrfToken->generateToken());

#            throw new ResolverException("class <$class> does not exists", ResolverException::INVALID_DATA_GIVEN);
        }





        return $handler->handle($request)->withHeader('X-CSRF-Token', $this->xsrfToken->generateToken());
    }
}
