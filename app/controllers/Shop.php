<?php

class Shop {
    public function get_index() {
        $items = simplexml_load_file(CONFIG_PATH . '/xml/shop.xml');

        $pagination = '';
        if (Settings::get('app.shop.per_page')) {
            $itemsPerPage = Settings::get('app.shop.per_page');
            $totalItems = count($items);
            $totalPages = ceil($totalItems / $itemsPerPage);

            $pagination = '<ul class="pagination">';
            for($p=1;$p<=$totalPages;$p++) {
                $active = ($p == 1) ? 'class="active"' : '';

                $pagination .= '<li ' . $active . '><a href="javascript: void(0)" data-page="' . $p . '">' . $p .  '</a></li>';
            }
            $pagination .= '</ul>';
        }

        return View::make('shop', ['items' => $items, 'pagination' => $pagination]);
    }

    public function post_buy() {
        $itemData = json_decode($_POST['item_data']);
        $userBalance = Auth::user()->balance;

        if ( ! Session::has('character_obj_id')) {
            return Output::json(Language::_('Nepasirinktas veikėjas'));
        }

        $_itemData = json_decode(json_encode($itemData), true);

        $characterId = Session::get('character_obj_id');

        if (isset($itemData->item)) {
            if ($userBalance < $_itemData['@attributes']['price']) {
                return Output::json(Language::_('Jūsų vartotojo balansas nepakankamas'));
            }

            $newUserBalance = $userBalance - $_itemData['@attributes']['price'];
            DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
                ':balance' => $newUserBalance,
                ':id' => Session::get('donate_user_id')
            ]);

            /// group
            foreach ($itemData->item as $key => $row) {
                $results = DB::first('SELECT max(' . SQL::get('sql.items.object_id') . ') as maxObjId FROM ' . SQL::get('sql.items.items'), [], 'server');

                $maxObjId = $results->maxObjId;
                if ( ! $results->maxObjId)
                    $maxObjId = 1;

                // find consume type
                $consumeType = XML::getItemConsumeType($row->id);

                // is stackable?
                if ($consumeType != 'stackable' && $consumeType != 'asset') {
                    // not stackable
                    for($i=1;$i <= $row->count;$i++) {
                        if ($i != 1)
                            $maxObjId = $maxObjId + 1;
                        else
                            $maxObjId = $maxObjId + 1;

                        DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                            ':owner_id' => $characterId,
                            ':object_id' => $maxObjId,
                            ':item_id' => $row->id,
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
                        'item_id' => $row->id
                    ], 'server');

                    if (isset($results->owner_id)) {
                        $newCount = $results->count + $row->count;

                        DB::query("UPDATE " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.count') . " = :count WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id ", [
                            ':owner_id' => $characterId,
                            ':item_id' => $row->id,
                            ':count' => $newCount
                        ], 'server');
                    } else {
                        DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                            ':owner_id' => $characterId,
                            ':object_id' => $maxObjId,
                            ':item_id' => $row->id,
                            ':count' => $row->count,
                            ':enchant_level' => 0,
                            ':loc' => 'INVENTORY'
                        ], 'server');
                    }
                }
            }

            Histuar::add(Language::_('Parduotuvė'), Language::_('Nupirkta prekių grupė: %s, kaina: %s', [$_itemData['title'], $_itemData['price']]));
        } else {
            // single
            if ($userBalance < $itemData->price) {
                return Output::json(Language::_('Jūsų vartotojo balansas nepakankamas'));
            }

            $newUserBalance = $userBalance - $itemData->price;
            DB::query("UPDATE users SET balance = :balance WHERE id = :id", [
                ':balance' => $newUserBalance,
                ':id' => Session::get('donate_user_id')
            ]);

            // obj id
            $results = DB::first('SELECT max(' . SQL::get('sql.items.object_id') . ') as maxObjId FROM ' . SQL::get('sql.items.items'), [], 'server');

            $maxObjId = $results->maxObjId;
            if ( ! $results->maxObjId)
                $maxObjId = 1;

            // find consume type
            $consumeType = XML::getItemConsumeType($itemData->id);
            
            // is stackable?
            if ($consumeType != 'stackable' && $consumeType != 'asset') {
                // not stackable
                for($i=1;$i <= $itemData->count;$i++) {
                    if ($i != 1)
                        $maxObjId = $maxObjId + 1;
                    else
                        $maxObjId = $maxObjId + 1;

                    DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                        ':owner_id' => $characterId,
                        ':object_id' => $maxObjId,
                        ':item_id' => $itemData->id,
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
                    'item_id' => $itemData->id
                ], 'server');

                if (isset($results->owner_id)) {
                    $newCount = $results->count + $itemData->count;

                    DB::query("UPDATE " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.count') . " = :count WHERE " . SQL::get('sql.items.owner_id') . " = :owner_id AND " . SQL::get('sql.items.item_id') . " = :item_id ", [
                        ':owner_id' => $characterId,
                        ':item_id' => $itemData->id,
                        ':count' => $newCount
                    ], 'server');
                } else {
                    DB::query("INSERT INTO " . SQL::get('sql.items.items') . " SET " . SQL::get('sql.items.owner_id') . " = :owner_id, " . SQL::get('sql.items.object_id') . " = :object_id, " . SQL::get('sql.items.item_id') . " = :item_id, " . SQL::get('sql.items.count') . " = :count, " . SQL::get('sql.items.enchant_level') . " = :enchant_level, " . SQL::get('sql.items.loc') . " = :loc", [
                        ':owner_id' => $characterId,
                        ':object_id' => $maxObjId,
                        ':item_id' => $itemData->id,
                        ':count' => $itemData->count,
                        ':enchant_level' => 0,
                        ':loc' => 'INVENTORY'
                    ], 'server');
                }
            }

            Histuar::add(Language::_('Parduotuvė'), Language::_('Nupirkta prekė: %s, kaina: %s', [$itemData->title, $itemData->price]));
        }

        return Output::json(['content' => Language::_('Prekė nupirkta sėkmingai'), 'type' => 'success', 'balance' => $newUserBalance]);
    }


}
