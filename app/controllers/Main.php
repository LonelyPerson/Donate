<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\View;

class Main {
    public function get_index() {
        return view('master');
    }
}
