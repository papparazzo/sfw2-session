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

abstract class SessionAbstract implements SessionInterface {

    protected string $path       = self::GLOBAL_SECTION;
    protected string $serverName = '';

    public function __construct(string $serverName) {
        $this->serverName = $serverName;
        $this->startSession();
    }

    public function setPath(string $path): void {
        if(!empty($path)) {
            $this->path = 'p' . $path;
        }
    }

    public function isPathEntrySet(string $index): bool {
        return $this->isEntrySet($this->path, $index);
    }

    public function getPathEntry(string $index, $default = null) {
        return $this->getEntry($this->path, $index, $default);
    }

    public function setPathEntry(string $index, $val) {
        $this->setEntry($this->path, $index, $val);
    }

    public function delPathEntry(string $index) {
        return $this->delEntry($this->path, $index);
    }

    public function delAllPathEntries() {
        return $this->delAllEntries($this->path);
    }

    public function isGlobalEntrySet(string $index) {
        return $this->isEntrySet(self::GLOBAL_SECTION, $index);
    }

    public function getGlobalEntry(string $index, $default = null) {
        return $this->getEntry(self::GLOBAL_SECTION, $index, $default);
    }

    public function setGlobalEntry(string $index, $val) {
        $this->setEntry(self::GLOBAL_SECTION, $index, $val);
    }

    public function delGlobalEntry(string $index) {
        return $this->delEntry(self::GLOBAL_SECTION, $index);
    }

    public function delAllGlobalEntries() {
        return $this->delAllEntries(self::GLOBAL_SECTION);
    }

    abstract protected function startSession();

    abstract protected function isEntrySet(string $section, string $index): bool;

    abstract protected function getEntry(string $section, string $index, $default = null);

    abstract protected function setEntry(string $section, string $index, $val);

    abstract protected function delEntry(string $section, string $index): bool;

    abstract protected function delAllEntries(string $section): bool;

    private function __clone() {
    }
}
