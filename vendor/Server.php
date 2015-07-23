<?php

class Server {
    public static function get($key, $serverID) {
        $server = include CONFIG_PATH . '/server.php';

        return $server[$serverID][$key];
    }

    public static function getID($id) {
        $server = include CONFIG_PATH . '/database.php';

        return $server['servers'][$id]['id'];
    }

    public static function getHashType($serverID) {
        $server = self::getID($serverID);
        $pack = self::get('pack', $server);

        if ($pack == 'fandc') {
            $hashType = 'whirlpool';
        } else {
            $hashType = 'default';
        }

        return $hashType;
    }
}
