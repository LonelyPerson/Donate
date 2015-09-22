<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\Settings;

class Language {
    private static $translations = [];

    public static function getActive() {
        return (Session::get('active_language')) ? Session::get('active_language') : 'lt';
    }

    public static function load($code = 'lt') {
        if (self::getActive() && self::getActive() != 'lt' && file_exists(LANGUAGES_PATH . '/' . self::getActive() . '.lang.php')) {
            self::$translations = include LANGUAGES_PATH . '/' . self::getActive() . '.lang.php';
        }
    }

    public static function _($key, $args = []) {
        if (isset(self::$translations[$key]) && ! empty(self::$translations[$key])) {
            if ($args) {
                return vsprintf(self::$translations[$key], $args);
            } else {
                return self::$translations[$key];
            }
        }

        // temp
        $base = (array) include APP_PATH . '/languages/base.php'; 
        $base[$key] = '';
        file_put_contents(APP_PATH . '/languages/base.php', '<?php return ' . var_export($base, true) . ';');

        return ($args) ? vsprintf($key, $args) : $key;
    }

    public static function getEnabled() {
        $languages = Settings::get('app.language.enabled');
       
        $languages = explode(',', $languages);
        return $languages;
    }
}
