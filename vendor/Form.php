<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\Input;

class Form {
	public static function token($form = '') {
		$token = sha1(md5(time() . uniqid()));

		Session::put('form_' . $form . '_token', $token);

		return $token;
	}

	public static function isTokenCorrect($form = '') {
		if (Session::has('form_' . $form . '_token')) {
			if (Session::get('form_' . $form . '_token') == Input::get('token')) {
				return true;
			}
		}

		return false;
	}
}