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

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SFW2\Session\XSRFToken;

class XSRFTokenTest extends TestCase
{
    private CacheInterface $cacheMock;
    private XSRFToken $xsrfToken;
    private const string TOKEN_NAME = XSRFToken::XSRF_TOKEN;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cacheMock = $this->createMock(CacheInterface::class);
        $this->xsrfToken = new XSRFToken($this->cacheMock);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCompareToken(): void
    {
        $validToken = 'valid_token_12345';

        // Test case 1: Valid token match
        $this->cacheMock->expects(self::once())
            ->method('get')
            ->with(self::TOKEN_NAME)
            ->willReturn($validToken);
        
        $this->cacheMock->expects(self::once())
            ->method('delete')
            ->with(self::TOKEN_NAME);

        self::assertTrue($this->xsrfToken->compareToken($validToken));
        
        // Reset mock for next test
        $this->setUp();
        
        // Test case 2: Invalid token
        $this->cacheMock->expects(self::once())
            ->method('get')
            ->with(self::TOKEN_NAME)
            ->willReturn($validToken);
            
        $this->cacheMock->expects(self::once())
            ->method('delete')
            ->with(self::TOKEN_NAME);

        self::assertFalse($this->xsrfToken->compareToken('invalid_token'));
        
        // Reset mock for next test
        $this->setUp();
        
        // Test case 3: No token in cache
        $this->cacheMock->expects(self::once())
            ->method('get')
            ->with(self::TOKEN_NAME)
            ->willReturn(null);
            
        $this->cacheMock->expects(self::never())
            ->method('delete');

        self::assertFalse($this->xsrfToken->compareToken($validToken));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetToken(): void
    {
        $storedToken = 'stored_token_67890';
        
        $this->cacheMock->expects(self::once())
            ->method('get')
            ->with(self::TOKEN_NAME)
            ->willReturn($storedToken);
            
        $this->cacheMock->expects(self::once())
            ->method('delete')
            ->with(self::TOKEN_NAME);

        $retrievedToken = $this->xsrfToken->getToken();
        self::assertEquals($storedToken, $retrievedToken);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGenerateToken(): void
    {
        $this->cacheMock->expects(self::once())
            ->method('set')
            ->with(
                self::TOKEN_NAME,
                self::callback(function($token) {
                    return is_string($token) && !empty($token) && strlen($token) === 32; // MD5 is 32 chars
                }),
                null
            );

        $token = $this->xsrfToken->generateToken();
        self::assertNotEmpty($token);
        self::assertIsString($token);
        self::assertEquals(32, strlen($token)); // MD5 hash length
    }
}
