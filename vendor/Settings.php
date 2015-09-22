<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\URL;
use \Donate\Vendor\DB;

class Settings {
    private static $settings = array();

    public static function load() {
        $database = include CONFIG_PATH . '/database.php';
        $app = [];
        $results = DB::get('SELECT * FROM config');

        foreach ($results as $key => $row) {
        	$app[$row->param_key] = $row->param_value;
        }

        self::$settings['database'] = $database;

        if ($app['base_url'] == '')
            $app['base_url'] = URL::baseUrl();

        $app['css'] = $app['base_url'] . '/assets/css';
        $app['js'] = $app['base_url'] . '/assets/js';
        $app['img'] = $app['base_url'] . '/assets/img';

        $app['admin_css'] = $app['base_url'] . '/assets/admin/css';
        $app['admin_js'] = $app['base_url'] . '/assets/admin/js';
        $app['admin_img'] = $app['base_url'] . '/assets/admin/img';

        self::$settings['app'] = $app;
    }

    public static function get($string) {
        $keyLevel2 = false;
        $keyLevel3 = false;

        if (strpos($string, '.') !== false) {
            $ex = explode('.', $string);

            $key = $ex[0];
            if ($key == 'app') {
                $ex = explode('.', $string, 2);
                $key2 = $ex[1];

                return self::$settings['app'][$key2];
            }

            if (isset($ex[1]))
                $keyLevel2 = $ex[1];

            if (isset($ex[2]))
                $keyLevel3 = $ex[2];

            if (isset($ex[3]))
                $keyLevel4 = $ex[3];
        }

        if (isset(self::$settings[$key])) {
            if ($keyLevel2 && isset(self::$settings[$key][$keyLevel2])) {
                if ($keyLevel3 && isset(self::$settings[$key][$keyLevel2][$keyLevel3])) {
                    if ($keyLevel4 && isset(self::$settings[$key][$keyLevel2][$keyLevel3][$keyLevel4])) {
                        return self::$settings[$key][$keyLevel2][$keyLevel3][$keyLevel4];
                    }

                    return self::$settings[$key][$keyLevel2][$keyLevel3];
                }

                return self::$settings[$key][$keyLevel2];
            }

            return self::$settings[$key];
        }
    }

    public static function has($key) {
        if (isset(self::$settings['app'][$key])) {
            return true;
        }

        return false;
    }
}
