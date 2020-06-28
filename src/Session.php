<?php

/**
 *  SFW2 - SimpleFrameWork
 *
 *  Copyright (C) 2017  Stefan Paproth
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

class Session {
    #http://de3.php.net/manual/en/session.security.php#87608
    #http://www.php.net/manual/de/function.setcookie.php#94398

    const GLOBAL_SECTION         = 'global';
    const XSS_TOKEN              = 'xss_token';

    protected string $path       = self::GLOBAL_SECTION;
    protected string $serverName = '';

    public function __construct(string $serverName) {
        $this->serverName = $serverName;
        $this->startSession();
    }

    public function __destruct() {
        session_write_close();
    }

    public function regenerateSession() : void {
        session_regenerate_id();
    }

    public function setPath(string $path) {
        if(!empty($path)) {
            $this->path = 'p' . $path;
        }
    }

    public function destroySession() : void {
        $domain = filter_var($this->serverName, FILTER_SANITIZE_URL);
        setcookie(session_name(), '', time() - 42000, '/', $domain, true, true);
        session_destroy();
        $_SESSION = [];
    }

    public function isPathEntrySet(string $index) {
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

    public function generateToken() : string {
        $token = md5(random_int(PHP_INT_MIN, PHP_INT_MAX) . uniqid("", true));
        $this->setGlobalEntry(self::XSS_TOKEN, $token);
        return $token;
    }

    public function compareToken(string $rtoken) : bool {
        $token = $this->getGlobalEntry(self::XSS_TOKEN);
        if($token == null) {
            return false;
        }
        $this->delGlobalEntry(self::XSS_TOKEN);
        return ($rtoken == $token);
    }

    public function getToken() : string {
        $token = $this->getGlobalEntry(self::XSS_TOKEN);
        $this->delGlobalEntry(self::XSS_TOKEN);
        return $token;
    }

    protected function startSession() {
        $domain = filter_var($this->serverName, FILTER_SANITIZE_URL);
        ini_set("session.use_only_cookies", "1");
        ini_set("session.cookie_lifetime", "1800");
        ini_set("session.cookie_httponly", "1");
        ini_set("session.bug_compat_42", "0");
        ini_set("session.bug_compat_warn", "0");

        session_set_cookie_params(1800, '/', $domain, false, true);
        session_start();
    }

    protected function isEntrySet(string $section, string $index) : bool {
        if(isset($_SESSION[$section][$index])) {
            return true;
        }
        return false;
    }

    protected function getEntry(string $section, string $index, $default = null) {
        if(!$this->isEntrySet($section, $index)) {
            return $default;
        }
        return unserialize($_SESSION[$section][$index]);
    }

    protected function setEntry(string $section, string $index, $val) {
        $_SESSION[$section][$index] = serialize($val);
    }

    protected function delEntry(string $section, string $index) : bool {
        if(!$this->isEntrySet($section, $index)) {
            return false;
        }
        unset($_SESSION[$section][$index]);
        return true;
    }

    protected function delAllEntries(string $section) : bool {
        if(!isset($_SESSION[$section])) {
            return false;
        }
        unset($_SESSION[$section]);
        return true;
    }

    private function __clone() {
    }
}