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

declare(strict_types=1);

namespace SFW2\Session;

use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class SessionSimpleCache implements CacheInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly string $section,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session->getEntry($key, $default, $this->section);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $this->session->setEntry($key, $value, $this->section);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return $this->session->deleteEntry($key, $this->section);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->session->deleteSection($this->section);
    }

    /**
     * @inheritDoc
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $tmp = [];
        foreach ($keys as $key) {
            $tmp[$key] = $this->get($key, $default);
        }
        return $tmp;
    }

    /**
     * @param  iterable<string, mixed> $values
     * @param  DateInterval|int|null   $ttl
     * @return bool
     * @throws InvalidArgumentException
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->session->hasEntry($key, $this->section);
    }
}