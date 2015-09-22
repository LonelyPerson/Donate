<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class String {
	public static function truncate($string, $length = 50, $append="..") {
		$string = trim($string);

		if(strlen($string) > $length) {
		    $string = wordwrap($string, $length);
		    $string = explode("\n", $string, 2);
		    $string = $string[0] . $append;
		}

		return $string;
	}
}
