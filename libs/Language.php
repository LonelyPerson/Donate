<?php

class Language {
    private static $translations = [];
    
    public static function getActive() {
        return (Session::get('active_language')) ? Session::get('active_language') : 'lt';
    }
    
    public static function load($code = 'lt') {
        if (Language::getActive() && Language::getActive() != 'lt' && file_exists(ROOT_PATH . '/settings/xml/languages/' . Language::getActive() . '.xml')) {
            $xml = simplexml_load_file(ROOT_PATH . '/settings/xml/languages/' . Language::getActive() . '.xml');

            if ($xml) {
                foreach ($xml as $translation) {
                    self::$translations[(string)$translation['key']] = (string)$translation['value'];
                }
            }
        }
    }
    
    public static function _($key, $args = []) {
        if (isset(self::$translations[$key])) {
            if ($args) {
                return vsprintf(self::$translations[$key], $args);
            } else {
                return self::$translations[$key];
            }
        }
        
        return ($args) ? vsprintf($key, $args) : $key;
    }
    
    public static function getEnabled() {
        return Settings::get('app.language.enabled');
    }
}
