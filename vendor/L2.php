<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class L2 {
    public static function hash($string, $type = 'default') {
        if ($type == 'whirlpool') {
            $hashedString = base64_encode(pack("H*", hash('whirlpool', utf8_encode($string))));
        } else {
            $hashedString = base64_encode(pack("H*", sha1(utf8_encode($string))));
        }

        return $hashedString;
    }
}