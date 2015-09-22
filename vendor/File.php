<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Settings;

class File {
    public static function getFlagIcon($code, $width = 0) {
        $code = strtoupper($code);
        $flagPath = Settings::get('app.base_url') . '/assets/img/flags';

        if (file_exists(ROOT_PATH . '/assets/img/flags/' . $code . '.png'))
            return '<img src="' . $flagPath . '/' . $code . '.png" />';

        return $code;
    }

    public static function randomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++)
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];

        return $string;
    }

    public static function read($file) {
        $content = @fread(@fopen($file, 'r'), @filesize($file)) or die('File::read error');

        return $content;
    }

    public static function create($path) {
        $h = fopen($path, "w") or die("File::create error");
        fclose($h);
    }

    public static function append($path, $string) {
        $fh = fopen($path, 'a') or die("File::append error");
        fwrite($fh, $string);
        fclose($fh);
    }

    public static function write($path, $string) {
        $fh = fopen($path, 'w') or die("File::append error");
        fwrite($fh, $string);
        fclose($fh);
    }
}
