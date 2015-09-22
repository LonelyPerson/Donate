<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\View;

class Information {
    public function get_index() {
        $message = Session::get('message');

        Session::forget('message');

        return view('information', ['message' => $message]);
    }
}
