<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class Input {
    public static function get($key = '') {
        if (isset($_POST[$key]) && ! empty($_POST[$key])) {
            return $_POST[$key];
        }
        
        if (isset($_POST[$key]) && $_POST[$key] === 0) {
            return $_POST[$key];
        }
        
        return false;
    }
}