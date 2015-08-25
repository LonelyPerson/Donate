<?php

class Inventory {
    public function get_index() {
        $items = DB::get("SELECT * FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = ?", [Session::get('character_obj_id')], 'server');

        $sql_objectId = SQL::get('sql.items.object_id');
        $sql_itemId = SQL::get('sql.items.item_id');
        $sql_count = SQL::get('sql.items.count');
        $sql_enchantLevel = SQL::get('sql.items.enchant_level');
        $sql_loc = SQL::get('sql.items.loc');
        $sql_ownerId = SQL::get('sql.items.owner_id');

        return View::make('inventory', [
            'items' => $items,
            'sql_objectId' => $sql_objectId,
            'sql_itemId' => $sql_itemId,
            'sql_count' => $sql_count,
            'sql_enchantLevel' => $sql_enchantLevel,
            'sql_ownerId' => $sql_ownerId,
            'sql_loc' => $sql_loc
        ]);
    }

    public function post_delete() {
        $objectId = Input::get('object_id');
        $count = Input::get('count');

        $sql_count = SQL::get('sql.items.count');

        $item = DB::first("SELECT * FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = '" . Session::get('character_obj_id') . "' AND " . SQL::get('sql.items.object_id') . " = ?", [$objectId], 'server');

        if ($item) {
            if ($item->$sql_count > 1) {
                if ( ! $count) {
                    return Output::json(['type' => 'error', 'message' => 'Neįvedėte trinamo daiktų kiekio']);
                } else {
                    if ($count >= $item->$sql_count) {
                        DB::query("DELETE FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = '" . Session::get('character_obj_id') . "' AND " . SQL::get('sql.items.object_id') . " = ?", [$objectId], 'server');
                    } else {
                        $newCount = $item->$sql_count - $count;

                        DB::query("UPDATE " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.count') . " = ? WHERE " . SQL::get('sql.items.owner_id') . " = '" . Session::get('character_obj_id') . "' AND " . SQL::get('sql.items.object_id') . " = ?", [$newCount, $objectId], 'server');
                    }
                }
            } else {
                DB::query("DELETE FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = '" . Session::get('character_obj_id') . "' AND " . SQL::get('sql.items.object_id') . " = ?", [$objectId], 'server');
            }
        }

        return Output::json(['type' => 'success']);
    }
}
