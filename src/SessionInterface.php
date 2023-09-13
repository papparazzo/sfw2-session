<?php declare(strict_types=1);

/*
 *  SFW2 - SimpleFrameWork
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

interface SessionInterface {
    public const GLOBAL_SECTION = 'global';

    public function regenerateSession(): void;

    public function setPath(string $path): void;

    public function destroySession(): void;

    public function hasPathEntry(string $index): bool;

    public function getPathEntry(string $index, $default = null);

    public function setPathEntry(string $index, $val);

    public function delPathEntry(string $index): bool;

    public function delAllPathEntries(): bool;

    public function hasGlobalEntry(string $index): bool;

    public function getGlobalEntry(string $index, $default = null);

    public function setGlobalEntry(string $index, $val);

    public function delGlobalEntry(string $index): bool;

    public function delAllGlobalEntries(): bool;

}
