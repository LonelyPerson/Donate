<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Settings;
use \Donate\Vendor\SessionStorage;

class Session {
    public static function type() {
        $type = Settings::get('app.session');

        return $type;
    }

    public static function handler() {
        return new SessionStorage();
    }

    public static function get($key) {
        if (self::type() == 'database')
            return self::handler()->read($key);

        if (isset($_SESSION[$key]))
            return $_SESSION[$key];

        return false;
    }

    public static function pull($key) {
        if (self::type() == 'database')
            return self::handler()->readAndDestroy($key);

        if (isset($_SESSION[$key])) {
            $session = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $session;
        }

        return false;
    }

    public static function put($key, $value) {
        if (self::type() == 'database')
            return self::handler()->write($key, $value);

        $_SESSION[$key] = $value;
    }

    public static function forget($key) {
        if (self::type() == 'database')
            return self::handler()->destroy($key);

        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    public static function has($key) {
        if (self::type() == 'database')
            return self::handler()->has($key);

        if (isset($_SESSION[$key]))
            return true;

        return false;
    }

    public static function destroy() {
        if (self::type() == 'database')
            return self::handler()->destroyAll();

        $_SESSION = [];
    }
}
