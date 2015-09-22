<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class Currency {
    public static function format($number, $type = 'price') {
        switch($type) {
            case 'price':
            case 'balance':
                $number = number_format((float) $number, 2, '.', ' ') . ' DC';
                break;
            default:
                $number = number_format((float) $number, 0, '.', ' ');
                break;
        }

        return $number;
    }
}
