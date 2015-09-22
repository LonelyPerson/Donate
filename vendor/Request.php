<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class Request {
	public static function isMethod($method) {
		if (strtolower($_SERVER['REQUEST_METHOD']) == $method)
			return true;

		return false;
	}
}