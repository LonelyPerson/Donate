<?php

class Server {
    public static function get($key, $serverID) {
        $server = include ROOT_PATH . '/settings/server.php';

        return $server[$serverID][$key];
    }

    public static function getID($id) {
        $server = include ROOT_PATH . '/settings/database.php';

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