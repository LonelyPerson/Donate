<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;

class Redirect {
	public static $redirect;

	public static function to($where) {
		header('Location: ' . config('app.base_url') . '/' . $where);
	}

	public static function back($params = []) {
		self::$redirect = Session::get('back');

		foreach ($params as $key => $value) {
			Session::put($key, $value);
		}

		header('Location: ' . self::$redirect);
	}
}