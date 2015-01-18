<?php

class L2 {
    public static function hash($string) {
	return base64_encode(pack("H*", sha1(utf8_encode($string))));
    }
}