<?php

/*
 *  Project:    sfw2-session
 *
 *  Copyright (C) 2020 Stefan Paproth <pappi-@gmx.de>
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

namespace SFW2\Session;

use Exception;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @noinspection PhpUnused
 */
class XSRFToken
{
    public const string XSRF_TOKEN = 'sfw2_xsrf_token';

    public function __construct(
        protected CacheInterface $cache
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function generateToken(): string
    {
        $token = md5(random_int(PHP_INT_MIN, PHP_INT_MAX) . uniqid("", true));
        $this->cache->set(self::XSRF_TOKEN, $token);
        return $token;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function compareToken(string $rtoken): bool
    {
        $token = $this->cache->get(self::XSRF_TOKEN);
        if ($token == null) {
            return false;
        }
       
        $this->cache->delete(self::XSRF_TOKEN);
        return ($rtoken == $token);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getToken(): string
    {
        $token = $this->cache->get(self::XSRF_TOKEN);
        $this->cache->delete(self::XSRF_TOKEN);
        return $token;
    }
}
