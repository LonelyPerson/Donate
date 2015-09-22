<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class URL {
    public static function to($where = '/') {
        Header('Location: ' . config('app.base_url') . $where);
        exit;
    }

    public static function baseUrl($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return rtrim($base_url, '/');
    }

    public static function segments($url) {
        $parse = parse_url($url);
        $path = ltrim($parse['path'], '/');
        $elements = explode('/', $path);

        $args = [];
        $counter = 1;
        foreach ($elements as $key => $value) {
            if ($value) {
                $args[$counter] = $value;
                $counter++;
            }
        }

        return $args;
    }

    public static function urlOrigin($s, $use_forwarded_host=false) {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    public static function current() {
        return self::urlOrigin($_SERVER) . $_SERVER['REQUEST_URI'];
    }
}
