<?php

class Currency {
    public static function format($number, $type = 'price') {
        switch($type) {
            case 'price':
            case 'balance':
                $number = number_format((float) $number, 2, '.', ' ') . ' &euro;';
                break;
            default:
                $number = number_format((float) $number, 0, '.', ' ');
                break;
        }

        return $number;
    }
}
