<?php

class Player {
    public static function isOnline($id) {
        if ( ! Settings::get('app.player.online_check'))
            return false;

        $player = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.obj_Id') . " = ?", [$id], 'server');

        $sql_online = SQL::get('sql.characters.online');

        if ($player->$sql_online == 1)
            return true;

        return false;
    }
}
