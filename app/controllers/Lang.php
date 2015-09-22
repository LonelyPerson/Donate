<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Input;
use \Donate\Vendor\Session;
use \Donate\Vendor\Output;

class Lang {
    // set language
    public function post_language() {
        $language = Input::get('language');

        if ( ! empty($language))
            Session::put('active_language', $language);

        _log('Changed language to <strong>' . $language . '</strong>', 'user');

        return Output::json(['success' => 'ok']);
    }
}
