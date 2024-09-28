<?php

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

abstract class SessionAbstract implements SessionInterface
{
    protected string $path = self::GLOBAL_SECTION;

    public function __construct()
    {
        $this->startSession();
    }

    public function setPath(string $path): static
    {
        if (!empty($path)) {
            $this->path = "p$path";
        }
        return $this;
    }

    public function hasPathEntry(string $index): bool
    {
        return $this->hasEntry($this->path, $index);
    }

    public function getPathEntry(string $index, $default = null): mixed
    {
        return $this->getEntry($this->path, $index, $default);
    }

    public function setPathEntry(string $index, $val): static
    {
        $this->setEntry($this->path, $index, $val);
        return $this;
    }

    public function delPathEntry(string $index): bool
    {
        return $this->delEntry($this->path, $index);
    }

    public function delAllPathEntries(): bool
    {
        return $this->delAllEntries($this->path);
    }

    public function hasGlobalEntry(string $index): bool
    {
        return $this->hasEntry(self::GLOBAL_SECTION, $index);
    }

    public function getGlobalEntry(string $index, $default = null): mixed
    {
        return $this->getEntry(self::GLOBAL_SECTION, $index, $default);
    }

    public function setGlobalEntry(string $index, $val): static
    {
        $this->setEntry(self::GLOBAL_SECTION, $index, $val);
        return $this;
    }

    public function delGlobalEntry(string $index): bool
    {
        return $this->delEntry(self::GLOBAL_SECTION, $index);
    }

    public function delAllGlobalEntries(): bool
    {
        return $this->delAllEntries(self::GLOBAL_SECTION);
    }

    abstract protected function startSession(): void;

    abstract protected function hasEntry(string $section, string $index): bool;

    abstract protected function getEntry(string $section, string $index, $default = null): mixed;

    abstract protected function setEntry(string $section, string $index, $val): static;

    abstract protected function delEntry(string $section, string $index): bool;

    abstract protected function delAllEntries(string $section): bool;

    private function __clone()
    {
    }
}
