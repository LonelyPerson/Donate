<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Settings;
use \Donate\Vendor\DB;
use \Donate\Vendor\SQL;
use \Donate\Vendor\Session;

class Player {
    public static function isOnline($id) {
        if ( ! config('app.player.online_check'))
            return false;

        $player = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [$id], 'server');

        $sql_online = SQL::get('sql.characters.online');

        if ($player->$sql_online == 1)
            return true;

        return false;
    }

    public static function isSelected() {
        if (Session::has('character_obj_id'))
            return true;

        return false;
    }

    public static function getCharacters($account) {
        $results = [];

        $sql_charName = SQL::get('sql.characters.char_name');
        $sql_level = SQL::get('sql.characters.level');
        $sql_accountName = SQL::get('sql.characters.account_name');
        $sql_characters = SQL::get('sql.characters.characters');

        $characters = DB::get("SELECT " . $sql_charName . ", " . $sql_level . " FROM " . $sql_characters . " WHERE " . $sql_accountName . " = ?", [$account], 'server');

        foreach ($characters as $row) {
            $results[] = [
                'char_name' => $row->$sql_charName,
                'level' => $row->$sql_level
            ];
        }

        return $results;
    }
}
