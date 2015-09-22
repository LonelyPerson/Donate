<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\DB;

class Log {
	public static function add($description, $type = '', $author = '') {
		$server = (Session::has('active_server_id')) ? Session::has('active_server_id') : 1;

		if ( ! $author) {
			$author = (Session::has('server_account_login')) ? Session::get('server_account_login') : '';
		}

		if ($description)
			DB::query("INSERT INTO logs SET author = ?, action = ?, type = ?, created_at = ?, server = ?", [$author, $description, $type, date('Y-m-d H:i:s'), $server]);
	}
}