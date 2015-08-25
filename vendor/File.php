<?php

class File {
    public static function getItemIcon($imgName, $itemID = 0) {
        $item = XML::findItemByID($itemID);

        $itemIcon = '';
        if ($item) {
            foreach ($item->set as $set) {
                if ($set->attributes()->name == 'icon') {
                    $itemIcon = $set->attributes()->val;
                    $itemIcon = str_replace('icon.', '', $itemIcon);
                    break;
                }
            }
        }

        if ($itemIcon)
            return '<img src="' . Settings::get('app.img') . '/icons/' . $itemIcon . '.png" class="small" style="width: 32px; height: 32px;" />';
        else
            return '<img src="' . Settings::get('app.img') . '/icons/default_no_img.jpg" class="small" style="width: 32px; height: 32px;" />';

    }

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
}
