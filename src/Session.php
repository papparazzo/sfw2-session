<?php

/**
 *  SFW2 - SimpleFrameWork
 *
 *  Copyright (C) 2020  Stefan Paproth
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
 */

declare(strict_types=1);

namespace SFW2\Session;

/**
 * @noinspection PhpUnused
 */
class Session extends SessionAbstract
{
    public function __destruct()
    {
        session_write_close();
    }

    public function regenerateSession(): static
    {
        session_regenerate_id();
        return $this;
    }

    public function destroySession(): void
    {
        setcookie((string)session_name(), '', time() - 42000, '/');
        session_destroy();
        $_SESSION = [];
    }

    protected function startSession(int $lifetime = self::SESSION_LIFE_TIME): static
    {
        session_set_cookie_params($lifetime, '/', null, true, true);
        session_start();
        return $this;
    }

    protected function hasEntry(string $section, string $index): bool
    {
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        if (isset($_SESSION[$section][$index])) {
            return true;
        }
        return false;
    }

    protected function getEntry(string $section, string $index, mixed $default = null): mixed
    {
        if (!$this->hasEntry($section, $index)) {
            return $default;
        }
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible, argument.type */
        return unserialize($_SESSION[$section][$index]);
    }

    protected function setEntry(string $section, string $index, mixed $val): static
    {
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        $_SESSION[$section][$index] = serialize($val);
        return $this;
    }

    protected function delEntry(string $section, string $index): bool
    {
        if (!$this->hasEntry($section, $index)) {
            return false;
        }
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        unset($_SESSION[$section][$index]);
        return true;
    }

    protected function delAllEntries(string $section): bool
    {
        if (!isset($_SESSION[$section])) {
            return false;
        }
        unset($_SESSION[$section]);
        return true;
    }
}
