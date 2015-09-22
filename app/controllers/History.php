<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Settings;
use \Donate\Vendor\DB;
use \Donate\Vendor\Session;
use \Donate\Vendor\View;

class History {
    public function get_index() {
        $history = DB::get('SELECT * FROM history WHERE user_id = :user_id ORDER BY action_date DESC LIMIT 0,' . Settings::get('app.history.limit'), [
            ':user_id' => Session::get('donate_user_id')
        ]);

        return view('history', ['history' => $history]);
    }
}
