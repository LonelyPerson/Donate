<?php

class Settings {
    private static $settings = array();
    
    public static function load() {
        $database = include ROOT_PATH . '/settings/database.php';
        $sql = include ROOT_PATH . '/settings/sql.php';
        $app = include ROOT_PATH . '/settings/app.php';
        
        self::$settings['database'] = $database;
        self::$settings['sql'] = $sql;
        
        if ($app['base_url'] == '')
            $app['base_url'] = URL::baseUrl();
        
        $app['css'] = $app['base_url'] . '/assets/css';
        $app['js'] = $app['base_url'] . '/assets/js';
        $app['img'] = $app['base_url'] . '/assets/img';
        
        self::$settings['app'] = $app;
    }
    
    public static function get($key) {
        $keyLevel2 = false;
        $keyLevel3 = false;
        
        if (strpos($key, '.') !== false) {
            $ex = explode('.', $key);
            
            $key = $ex[0];
            
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
}
