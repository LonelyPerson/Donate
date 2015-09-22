<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Settings;
use \Donate\Vendor\String;
use \Donate\Vendor\Session;
use \Donate\Vendor\DB;

class Item {
    public static function getTitle($manualTitle, $autoTitle) {
        if (Settings::get('app.shop.auto_title')) :
            return String::truncate($autoTitle, 50);
        else:
            return String::truncate($manualTitle, 50);
        endif;
    }

    public static function getLoc($loc) {
        switch(strtolower($loc)) {
            case 'inventory':
                $loc = 'su savimi';
                break;
            case 'warehouse':
                $loc = 'saugykloje';
                break;
            case 'paperdoll':
                $loc = 'užsidėjęs';
                break;
            case 'clanwh':
                $loc = 'klano saugykloje';
                break;
        }

        return $loc;
    }

    public static function inMarket($object_id) {
        $owner_id = Session::get('character_obj_id');

        $result = DB::first('SELECT * FROM market WHERE owner_id = ? AND object_id = ?', [$owner_id, $object_id]);
        if ( ! $result)
            return false;

        return true;
    }

    public static function getIcon($icon) {
        if (file_exists(ROOT_PATH . '/assets/img/icons/' . $icon . '.png'))
            return '<img src="' . Settings::get('app.img') . '/icons/' . $icon . '.png" class="small" style="width: 32px; height: 32px;" />';
        else
            return '<img src="' . Settings::get('app.img') . '/icons/default_no_img.jpg" class="small" style="width: 32px; height: 32px;" />';
    }

    public static function getTitleFromDB($itemId) {
        $item = DB::first('SELECT * FROM items WHERE chronicle = ? AND item_id = ?', [Server::getItemChronicle(), $itemId]);

        return String::truncate($item->item_name, 50);
    }

    public static function getIconFromDB($itemId) {
        $item = DB::first('SELECT * FROM items WHERE chronicle = ? AND item_id = ?', [Server::getItemChronicle(), $itemId]);
        
        if (file_exists(ROOT_PATH . '/assets/img/icons/' . $item->icon . '.png'))
            return '<img src="' . Settings::get('app.img') . '/icons/' . $item->icon . '.png" class="small" style="width: 32px; height: 32px;" />';
        else
            return '<img src="' . Settings::get('app.img') . '/icons/default_no_img.jpg" class="small" style="width: 32px; height: 32px;" />';
    }

    public static function totalPrice($groupId) {
        $result = DB::first('SELECT SUM(price) as price FROM shop WHERE group_id = ?', [$groupId]);

        return $result->price;
    }

    public static function getGroupItems($groupId) {
        $results = DB::get('SELECT * FROM shop as s LEFT JOIN items as i ON s.item_id = i.item_id WHERE i.chronicle = ? AND s.group_id = ?', [Server::getItemChronicle(), $groupId]);

        return $results;
    }
}
