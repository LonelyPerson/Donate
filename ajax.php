<?php

if (strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST')) === false) exit('error #1');
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') exit('error #2');

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/libs/helpers/load.php');

if ( ! defined('STARTED')) exit('error #3');

if (isset($_GET['route'])) {
    $ex = array();
    if (strpos($_GET['route'], '::') !== false)
        $ex = explode('::', $_GET['route']);

    if ( ! isset($ex[1])) {
        $c = $_GET['route'];
        $m = 'index';
    } else {
        $c = $ex[0];
        $m = $ex[1];
    }
    
    if (($c != 'login' && $c != 'registration') && ! Auth::isLoggedIn()) exit('error #4'); 
    
    include ROOT_PATH . '/controllers/' . ucfirst($c) . '.php';

    $s = new $c();
    $s->$m();
    
    exit;
}

if (isset($_POST['auth'])) {
    $username = Input::get('username');
    $password = Input::get('password');
    $server = Input::get('server');

    if (Settings::get('app.captcha.login')) {
        $resp = recaptcha_check_answer(Settings::get('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

        if ( ! $resp->is_valid)
            return Output::json(Language::_('Neteisingai įvestas apsaugos kodas'));
    }
    
    if ( ! $username || ! $password) 
        return Output::json(Language::_('Užpildykite visus laukelius'));
    
    if (Auth::check($username, $password, $server))
        return Output::json(array('view' => 'user'));

    return Output::json(Language::_('Prisijungti nepavyko'));
}

if (isset($_POST['registration'])) {
    $username = Input::get('username');
    $password = Input::get('password');
    $server = Input::get('server');
    
    if (Settings::get('app.captcha.registration')) {
        $resp = recaptcha_check_answer(Settings::get('app.captcha.secret'), $_SERVER["REMOTE_ADDR"], Input::get("recaptcha_challenge_field"), Input::get("recaptcha_response_field"));

        if ( ! $resp->is_valid)
            return Output::json(Language::_('Neteisingai įvestas apsaugos kodas'));
    }
    
    if ( ! $username || ! $password) 
        return Output::json(Language::_('Užpildykite visus laukelius'));
    
    if (Settings::get('app.registration.min') && mb_strlen($password) < Settings::get('app.registration.min')) 
        return Output::json(Language::_('Minimalus slaptažodžio ilgis turi būti: %s simboliai (-ų)', [Settings::get('app.registration.min')]));

    if (Settings::get('app.registration.max') && mb_strlen($password) > Settings::get('app.registration.max')) 
        return Output::json(Language::_('Slaptažodis negali būti ilgesnis, nei: %s simboliai (-ų)', [Settings::get('app.registration.min')]));
    
    $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');
    $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));
    if (isset($serverResults->$loginFieldName))
        return Output::json(Language::_('Toks vartotojas jau užregistruotas'));

    $encodedPassword = L2::hash($password, Server::getHashType($server));
    
    DB::query('INSERT INTO ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?, ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $encodedPassword], 'server', $server, 'login');
    
    return Output::json(['content' => Language::_('Registracija sėkminga'), type => 'success', 'success' => 'ok']);
}

if (isset($_POST['set_language'])) {
    $language = Input::get('language');
    
    if ( ! empty($language))
        Session::put('active_language', $language);
    
    return Output::json(['success' => 'ok']);
}

if ( ! Auth::isLoggedIn()) exit('error #5');

if (isset($_POST['buy'])) {
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
            $consumeType = File::findXMLByItemID($row->id);

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
        $consumeType = File::findXMLByItemID($itemData->id);

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

if (isset($_POST['select_character'])) {
    $characterName = Input::get('character_name');
    $oldChar = '<a href="javascript: void(0);" class="select-char">' . Session::get('character_name') . '</a>';

    $characters = DB::first("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ? AND " . SQL::get('sql.characters.char_name') . " = ?", [Session::get('server_account_login'), $characterName], 'server');

    if ( ! $characters) {
        return Output::json(Language::_('Deja, bet nepavyko pasirinkti veikėjo'));
    }

    $charObjId = SQL::get('sql.characters.obj_Id');
    Session::put('character_obj_id', $characters->$charObjId);
    Session::put('character_name', $characterName);
    
    return Output::json(['success' => 'ok', 'content' => Language::_('Veikėjas pasirinktas'), 'type' => 'success', 'character_name' => $characterName, 'old_char' => $oldChar]);
}

if (isset($_POST['paypal'])) {
    $data = $_POST;
    
    if ( ! Input::get('item_number') || ! Input::get('sum'))
        return Output::json(Language::_('Neįvedėte taškų sumos'));
    
    $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('sum'));
    if ( ! $number)
        return Output::json(Language::_('Prašome įvesti tik skaičius'));
    
    if (Input::get('sum') < Settings::get('app.paypal.min'))
        return Output::json(Language::_('Minimalus taškų kiekis: %s', [Settings::get('app.paypal.min')]));
    
    if (Settings::get('app.paypal.max') && Input::get('sum') > Settings::get('app.paypal.max'))
        return Output::json(Language::_('Maksimalus taškų kiekis: %s', [Settings::get('app.paypal.max')]));
    
    $itemNumber = $data['item_number'];
    $amount = round(Settings::get('app.paypal.price') * $data['sum'], 2);

    unset($data['jas_paypal_submit']);
    
    $querystring .= "?business=" . urlencode(Settings::get('app.paypal.email')) . "&";	

    $querystring .= "item_name=" . urlencode(Settings::get('app.paypal.purpose')) . "&";
    $querystring .= "amount=" . urlencode($amount) . "&";
    $querystring .= "currency_code=" . urlencode(strtoupper(Settings::get('app.paypal.currency'))) . "&";
    $querystring .= "cmd=" . urlencode('_donations') . "&";
    $querystring .= "rm=" . urlencode('2') . "&";
    $querystring .= "no_note=" . urlencode('1') . "&";
    $querystring .= "bn=" . urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest') . "&";

    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $querystring .= "$key=$value&";
    }

    $querystring .= "return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/pay.php?id=paypal&action=verify')) . "&";
    $querystring .= "cancel_return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/pay.php?id=paypal&action=cancel')) . "&";
    $querystring .= "notify_url=" . urlencode(Settings::get('app.base_url') . '/pay.php?id=paypal&action=notify');

    $results = DB::first("SELECT * FROM paypal WHERE item_number = :item_number", [':item_number' => $data['item_number']]);
    if ( ! isset($results->id)) {
        DB::query("INSERT INTO paypal SET item_number = :item_number, status = :status, amount = :amount, currency = :currency, txn_id = :txn_id, user_id = :user_id, points = :points, ip = :ip, start_date = :start_date", [
            ':item_number' => $itemNumber,
            ':status' => 'waiting',
            ':amount' => $amount,
            ':currency' => strtoupper(Settings::get('app.paypal.currency')),
            ':txn_id' => '',
            ':user_id' => Session::get('donate_user_id'),
            ':points' => $data['sum'],
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':start_date' => date('Y-m-d H:i:s')
        ]);
    }

    if (Settings::get('app.paypal.test')) {
        return Output::json(['redirect' => 'https://www.sandbox.paypal.com/cgi-bin/webscr' . $querystring]);
    } else {
        return Output::json(['redirect' => 'https://www.paypal.com/cgi-bin/webscr' . $querystring]);
    }
}

if (isset($_POST['mokejimai'])) {
    $data = $_POST;
    
    if ( ! Input::get('order') || ! Input::get('sum'))
        return Output::json(Language::_('Neįvedėte taškų sumos'));
    
    $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('sum'));
    if ( ! $number)
        return Output::json(Language::_('Prašome įvesti tik skaičius'));
    
    if (Input::get('sum') < Settings::get('app.mokejimai.min'))
        return Output::json(Language::_('Minimalus taškų kiekis: %s', [Settings::get('app.mokejimai.min')]));
    
    if (Settings::get('app.mokejimai.max') && Input::get('sum') > Settings::get('app.mokejimai.max'))
        return Output::json(Language::_('Maksimalus taškų kiekis: %s', [Settings::get('app.mokejimai.max')]));
    
    $order = $data['order'];
    $amount = round(Settings::get('app.mokejimai.price') * $data['sum'], 2);
    $amount = $amount * 100;

    $result = DB::first("SELECT * FROM mokejimai WHERE orderid = :orderid", [':orderid' => $order]);
    if ( ! isset($result->order)) {
        DB::query("INSERT INTO mokejimai SET orderid = :orderid, user_id = :user_id, status = :status, amount = :amount, points = :points, ip = :ip, start_date = :start_date", [
            ':orderid' => $order,
            ':user_id' => Session::get('donate_user_id'),
            ':status' => 'waiting',
            ':amount' => $amount,
            ':currency' => Settings::get('app.mokejimai.currency'),
            ':points' => $data['sum'],
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':start_date' => date('Y-m-d H:i:s')
        ]);
    }
    
    try {
        $request = WebToPay::buildRequest(array(
            'projectid'     => Settings::get('app.mokejimai.id'),
            'sign_password' => Settings::get('app.mokejimai.secret'),
            'amount'        => $amount,
            'orderid'       => $order,
            'currency'      => strtoupper(Settings::get('app.mokejimai.currency')),
            'paytext'       => Settings::get('app.mokejimai.text') . ' (nr. [order_nr]) ([site_name])',
            'accepturl'     => Settings::get('app.base_url') . '/pay.php?id=mokejimai&action=verify',
            'cancelurl'     => Settings::get('app.base_url') . '/pay.php?id=mokejimai&action=cancel',
            'callbackurl'   => Settings::get('app.base_url') . '/pay.php?id=mokejimai&action=notify',
            'version'       => Settings::get('app.mokejimai.version'),
            'test'          => Settings::get('app.mokejimai.test')
        ));
    } catch (WebToPayException $e) {
        Output::json($e->getMessage());
    }

    return Output::json(['submit' => 'submit', 'sign' => $request['sign'], 'data' => $request['data']]);
}

if (isset($_POST['logout'])) {
    Auth::logout();

    return Output::json(['success' => 'ok']);
}