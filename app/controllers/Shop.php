<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\DB;
use \Donate\Vendor\SQL;
use \Donate\Vendor\View;
use \Donate\Vendor\Settings;
use \Donate\Vendor\Input;
use \Donate\Vendor\Output;
use \Donate\Vendor\Auth;
use \Donate\Vendor\L2;
use \Donate\Vendor\Mail;
use \Donate\Vendor\Player;
use \Donate\Vendor\Session;
use \Donate\Vendor\Histuar;
use \Donate\Vendor\Item;

class Shop {
    public function get_index() {
        $server = Session::get('active_server_id');
        $items = DB::get('SELECT * FROM shop as s LEFT JOIN items as i ON s.item_id = i.item_id WHERE i.chronicle = ? AND s.server = ? GROUP BY group_id', [Server::getItemChronicle(), $server]);

        $pagination = '';
        if (config('app.shop.per_page')) {
            $itemsPerPage = config('app.shop.per_page');
            $totalItems = count($items);
            $totalPages = ceil($totalItems / $itemsPerPage);

            if ($totalPages > 1) {
                $pagination = '<ul class="pagination">';
                for($p=1;$p<=$totalPages;$p++) {
                    $active = ($p == 1) ? 'class="active"' : '';

                    $pagination .= '<li ' . $active . '><a href="javascript: void(0)" data-page="' . $p . '">' . $p .  '</a></li>';
                }
                $pagination .= '</ul>';
            }
        }

        return View::make('shop', ['items' => $items, 'pagination' => $pagination]);
    }

    public function post_buy() {
        $userBalance = Auth::user()->balance;

        if ( ! Session::has('character_obj_id')) {
            return Output::json(__('No character selected'));
        }

        $itemId = Input::get('item_id');
        $groupId = Input::get('group_id');
        $isGroup = Input::get('is_group');
        $quantity = Input::get('quantity');
        $price = Input::get('price');
        $itemTitle = Input::get('title');
        $stackable = Input::get('stackable');

        $characterId = Session::get('character_obj_id');

        if ($isGroup) {
            $price = Item::totalPrice($groupId);

            if ($userBalance < $price) {
                return Output::json(__('Jūsų vartotojo balansas nepakankamas'));
            }

            $newUserBalance = $userBalance - $price;
            DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
                ':balance' => $newUserBalance,
                ':id' => Session::get('donate_user_id')
            ]);

            /// group
            $items = Item::getGroupItems($groupId); 
            foreach ($items as $key => $row) {
                $results = DB::first('SELECT max(' . SQL::get('sql.items.object_id') . ') as maxObjId FROM ' . SQL::get('sql.items.items'), [], 'server');

                $maxObjId = $results->maxObjId;
                if ( ! $results->maxObjId)
                    $maxObjId = 1;

                // find consume type
                $isStackable = $row->is_stackable;

                // is stackable?
                if ( ! $isStackable) {
                    // not stackable
                    for($i=1;$i <= $row->quantity;$i++) {
                        if ($i != 1)
                            $maxObjId = $maxObjId + 1;
                        else
                            $maxObjId = $maxObjId + 1;

                        DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                            ':owner_id' => $characterId,
                            ':object_id' => $maxObjId,
                            ':item_id' => $row->item_id,
                            ':count' => 1,
                            ':enchant_level' => 0,
                            ':loc' => 'INVENTORY'
                        ], 'server');
                    }
                } else {
                    // stackable
                    $maxObjId = $maxObjId + 1;

                    // same block
                    $results = DB::first("SELECT * FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id", [
                        'owner_id' => $characterId,
                        'item_id' => $row->item_id
                    ], 'server');

                    if (isset($results->owner_id)) {
                        $newCount = $results->count + $row->quantity;

                        DB::query("UPDATE " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.count') . " = :count WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id ", [
                            ':owner_id' => $characterId,
                            ':item_id' => $row->item_id,
                            ':count' => $newCount
                        ], 'server');
                    } else {
                        DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                            ':owner_id' => $characterId,
                            ':object_id' => $maxObjId,
                            ':item_id' => $row->item_id,
                            ':count' => $row->quantity,
                            ':enchant_level' => 0,
                            ':loc' => 'INVENTORY'
                        ], 'server');
                    }
                }
            }

             _log('Successfully purchased items group for <strong>' . $price . '</strong> DC', 'user');

            Histuar::add(__('Parduotuvė'), __('Items group purchased for <strong>%s</strong> DC', [$price]));
        } else {
            // single
            if ($userBalance < $price) {
                return Output::json(__('You do not have enough DC'));
            }

            $newUserBalance = $userBalance - $price;
            DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
                ':balance' => $newUserBalance,
                ':id' => Session::get('donate_user_id')
            ]);

            // obj id
            $results = DB::first('SELECT max(' . SQL::get('sql.items.object_id') . ') as maxObjId FROM ' . SQL::get('sql.items.items'), [], 'server');

            $maxObjId = $results->maxObjId;
            if ( ! $results->maxObjId)
                $maxObjId = 1;
            
            // is stackable?
            if ($stackable == 1) {
                // not stackable
                for($i=1;$i <= $quantity;$i++) {
                    if ($i != 1)
                        $maxObjId = $maxObjId + 1;
                    else
                        $maxObjId = $maxObjId + 1;

                    DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                        ':owner_id' => $characterId,
                        ':object_id' => $maxObjId,
                        ':item_id' => $itemId,
                        ':count' => 1,
                        ':enchant_level' => 0,
                        ':loc' => 'INVENTORY'
                    ], 'server');
                }
            } else {
                // stackable
                $maxObjId = $maxObjId + 1;

                // same block
                $results = DB::first("SELECT * FROM " . SQL::get('sql.items.items') . " WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id", [
                    'owner_id' => $characterId,
                    'item_id' => $itemId
                ], 'server');

                if (isset($results->owner_id)) {
                    $newCount = $results->count + $quantity;

                    DB::query("UPDATE " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.count') . " = :count WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id ", [
                        ':owner_id' => $characterId,
                        ':item_id' => $itemId,
                        ':count' => $newCount
                    ], 'server');
                } else {
                    DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                        ':owner_id' => $characterId,
                        ':object_id' => $maxObjId,
                        ':item_id' => $itemId,
                        ':count' => $quantity,
                        ':enchant_level' => 0,
                        ':loc' => 'INVENTORY'
                    ], 'server');
                }
            }

            _log('Successfully purchased item for <strong>' . $price . '</strong>', 'user');

            Histuar::add(__('Parduotuvė'), __('Item purchased successfully for <strong>%s</strong>', [$price]));
        }

        return Output::json(['content' => __('Item purchased successfully '), 'type' => 'success', 'balance' => $newUserBalance]);
    }


}
