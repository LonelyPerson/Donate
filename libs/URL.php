<?php

class URL {
    public static function to($where = '/') {
        Header('Location: ' . Settings::get('app.base_url') . $where);
    }
    
    function baseUrl() {
	$currentPath = $_SERVER['PHP_SELF']; 
	$pathInfo = pathinfo($currentPath); 
	$hostName = $_SERVER['HTTP_HOST']; 
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
	
	return trim($protocol . $hostName . $pathInfo['dirname'], '/');
    }
}