<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

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

    public static function getItemChronicle() {
        switch (self::get('chronicle', Session::get('active_server_id'))) {
            case 'c6':
                $chronicle = 'interlude';
                break;
            case 'gracia':
            case 'h5':
            case 'god':
                $chronicle = 'god';
                break;
            default:
                $chronicle = 'god';
        }

        return $chronicle;
    }
}
