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

interface SessionInterface
{
    public const string GLOBAL_SECTION = 'global';

    public function regenerateSession(): static;

    public function commitSession(): void;

    public function destroySession(): void;

    public function hasEntry(string $index, string $section = self::GLOBAL_SECTION): bool;

    public function getEntry(string $index, mixed $default = null, string $section = self::GLOBAL_SECTION): mixed;

    public function setEntry(string $index, mixed $val, string $section = self::GLOBAL_SECTION): static;

    public function deleteEntry(string $index, string $section = self::GLOBAL_SECTION): bool;

    public function deleteSection(string $section = self::GLOBAL_SECTION): bool;
}
