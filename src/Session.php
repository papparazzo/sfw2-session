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

class Session implements SessionInterface
{
    public function __construct(int $lifetime, string $name = 'SESSID')
    {
        session_set_cookie_params($lifetime, '/', null, true, true);
        session_start([
            'gc_maxlifetime' => $lifetime,
            'use_strict_mode' => true,
            'name' => $name
        ]);

        // Reset the expiration time upon a page load
        if (isset($_COOKIE[$name])) {
            setcookie($name, $_COOKIE[$name], time() + $lifetime, "/");
        }
    }

    public function regenerateSession(): static
    {
        session_regenerate_id();
        return $this;
    }

    public function commitSession(): void
    {
        session_write_close();
    }

    public function destroySession(): void
    {
        setcookie(
            name: (string)session_name(),
            expires_or_options: time() - 42000,
            path: '/',
            secure: true,
            httponly: true
        );
        session_destroy();
        $_SESSION = [];
    }

    public function hasEntry(string $index, string $section = self::GLOBAL_SECTION): bool
    {
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        if (isset($_SESSION[$section][$index])) {
            return true;
        }
        return false;
    }

    public function getEntry(string $index, mixed $default = null, string $section = self::GLOBAL_SECTION): mixed
    {
        if (!$this->hasEntry($section, $index)) {
            return $default;
        }
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible, argument.type */
        return unserialize($_SESSION[$section][$index]);
    }

    public function setEntry(string $index, mixed $val, string $section = self::GLOBAL_SECTION): static
    {
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        $_SESSION[$section][$index] = serialize($val);
        return $this;
    }

    public function deleteEntry(string $index, string $section = self::GLOBAL_SECTION): bool
    {
        if (!$this->hasEntry($section, $index)) {
            return false;
        }
        /** @phpstan-ignore offsetAccess.nonOffsetAccessible */
        unset($_SESSION[$section][$index]);
        return true;
    }

    public function deleteSection(string $section = self::GLOBAL_SECTION): bool
    {
        if (!isset($_SESSION[$section])) {
            return false;
        }
        unset($_SESSION[$section]);
        return true;
    }
}
