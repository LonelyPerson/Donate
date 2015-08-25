<?php

class Item {
    public static function getTitle($itemID, $itemTitle) {
        if (Settings::get('app.shop.auto_title')) :
            return String::truncate(XML::getTitle($itemID), 50);
        else:
            return String::truncate($itemTitle, 50);
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
}
