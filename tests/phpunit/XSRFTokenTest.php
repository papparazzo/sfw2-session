<?php

/**
 *  SFW2 - SimpleFrameWork
 *
 *  Copyright (C) 2024  Stefan Paproth
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

namespace phpunit;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SFW2\Session\XSRFToken;

class XSRFTokenTest extends TestCase
{

    public function testCompareToken()
    {
        self::markTestIncomplete();
    }

    public function testGetToken()
    {
        self::markTestIncomplete();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGenerateToken()
    {
        $mock = $this->getMockBuilder(CacheInterface::class)->getMock();

        $token = new XSRFToken($mock);
        self::assertNotEmpty($token->generateToken());
    }
}
