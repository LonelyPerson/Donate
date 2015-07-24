<?php

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

    // routes if not logged in
    if (($c != 'login' && $c != 'registration' && $c != 'recovery' && $c != 'information') && ! Auth::isLoggedIn()) exit('error #4');

    include CONTROLLERS_PATH . '/' . ucfirst($c) . '.php';

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

if (isset($_POST['recovery'])) {
    $input = Input::get('recovery_input');
    $server = Input::get('server');
    $userId = false;

    if ( ! $input)
        return Output::json(Language::_('Užpildykite visus laukelius'));

    if (strpos($input, '@') !== false) {
        $result = DB::first('SELECT * FROM users WHERE server = :server AND email = :email', [
            ':email' => $input,
            ':server' => $server
        ]);

        if ( ! $result)
            return Output::json(Language::_('Toks el. pašto adresas nerastas'));

        $userId = $result->id;
        $email = $result->email;
    } else {
        $result = DB::first('SELECT * FROM users WHERE username = :username', [
            ':username' => $input,
            ':server' => $server
        ]);

        if ( ! $result)
            return Output::json(Language::_('Vartotojas tokiu slapyvardžių nerastas'));

        $userId = $result->id;
        $email = $result->email;
    }

    if ($userId) {
        $result = DB::first('SELECT * FROM recovery WHERE user_id = :user_id AND active_until >= :active', [
            ':user_id' => $userId,
            ':active' => time()
        ]);

        if ($result)
            return Output::json(Language::_('Negalite keisti slaptažodžio dažniau, nei kartą per 12 valandų'));

        while(true) {
            $code = File::randomString(35);
            $result = DB::first('SELECT * FROM recovery WHERE code = ?', [$code]);
            if ( ! $result)
                break;
        }

        DB::query('INSERT INTO recovery SET user_id = :user_id, code = :code, server = :server, active_until = :active_until, add_date = :add_date', [
            ':user_id' => $userId,
            ':code' => $code,
            ':server' => $server,
            ':active_until' => strtotime("+12 hours", strtotime(date('Y-m-d H:i:s'))),
            ':add_date' => date('Y-m-d H:i:s')
        ]);

        $code = base64_encode($code);

        $verifyLink = Settings::get('app.base_url') . '/index.php/recovery/verify/' . $code;

        $message = Language::_('Jei tikrai norite gauti naują slaptažodį puslapyje paspauskite žemiau esančią patvirtinimo nuorodą', [Settings::get('app.base_url')]) . '<br />';
        $message .= $verifyLink;

        Mail::send($email, Language::_('Slaptažodžio keitimo patvirtinimas'), $message);

        return Output::json(['content' => Language::_('Slaptažodžio keitimo patvirtinimas nusiųstas Jums į el. paštą'), 'type' => 'success']);
    }
}

if (isset($_POST['set_language'])) {
    $language = Input::get('language');

    if ( ! empty($language))
        Session::put('active_language', $language);

    return Output::json(['success' => 'ok']);
}

if (isset($_POST['check_mysql_data'])) {
    if ( ! DB::isActive())
        return  Output::json(['status' => 'error']);

    return  Output::json(['status' => 'success']);
}

if (isset($_POST['check_chmod'])) {
    if ( ! is_writable(APP_PATH . '/storage')) {
        return Output::json(['status' => 'error']);
    }

    return Output::json(['status' => 'success']);
}

if (isset($_POST['install'])) {
    ini_set('memory_limit', -1);
    set_time_limit(0);

    $query = File::read(ROOT_PATH . '/install/donate.sql');
    $query = SQL::removeRemarks($query);
    $query = SQL::splitFile($query, ';');

    foreach($query as $sql){
        DB::query($sql);
    }

    File::create(STORAGE_PATH . '/installed');

    return Output::json(['status' => 'success']);
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

    $querystring .= "return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/index.php/paypal/verified')) . "&";
    $querystring .= "cancel_return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/index.php/paypal/cancel')) . "&";
    $querystring .= "notify_url=" . urlencode(Settings::get('app.base_url') . '/index.php/paypal/notify');

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

    $result = DB::first("SELECT * FROM mokejimai WHERE orderid = ?", [$order]);
    if ( ! isset($result->orderid)) {
        DB::query("INSERT INTO mokejimai SET orderid = :orderid, user_id = :user_id, status = :status, amount = :amount, currency = :currency, points = :points, ip = :ip, start_date = :start_date", [
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
            'accepturl'     => Settings::get('app.base_url') . '/index.php/paysera-bank/verified',
            'cancelurl'     => Settings::get('app.base_url') . '/index.php/paysera-bank/cancel',
            'callbackurl'   => Settings::get('app.base_url') . '/index.php/paysera-bank/notify',
            'version'       => Settings::get('app.mokejimai.version'),
            'test'          => Settings::get('app.mokejimai.test')
        ));
    } catch (WebToPayException $e) {
        Output::json($e->getMessage());
    }

    return Output::json(['submit' => 'submit', 'sign' => $request['sign'], 'data' => $request['data']]);
}

if (isset($_POST['paygol'])) {
    $data = $_POST;

    if ( ! Input::get('order') || ! Input::get('pg_price'))
        return Output::json(Language::_('Neįvedėte taškų sumos'));

    $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('pg_price'));
    if ( ! $number)
        return Output::json(Language::_('Prašome įvesti tik skaičius'));

    if (Input::get('pg_price') < Settings::get('app.paygol.min'))
        return Output::json(Language::_('Minimalus taškų kiekis: %s', [Settings::get('app.paygol.min')]));

    if (Settings::get('app.paygol.max') && Input::get('pg_price') > Settings::get('app.paygol.max'))
        return Output::json(Language::_('Maksimalus taškų kiekis: %s', [Settings::get('app.paygol.max')]));

    $order = $data['order'];
    $amount = round(Settings::get('app.paygol.price') * $data['pg_price'], 2);
    $amount = $amount * 100;

    $result = DB::first("SELECT * FROM paygol WHERE orderid = ?", [$order]);
    if ( ! isset($result->orderid)) {
        DB::query("INSERT INTO paygol SET orderid = :orderid, user_id = :user_id, status = :status, amount = :amount, currency = :currency, points = :points, ip = :ip, start_date = :start_date", [
            ':orderid' => $order,
            ':user_id' => Session::get('donate_user_id'),
            ':status' => 'waiting',
            ':amount' => $amount,
            ':currency' => Settings::get('app.paygol.currency'),
            ':points' => $data['pg_price'],
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':start_date' => date('Y-m-d H:i:s')
        ]);
    }

    return Output::json(['submit' => 'submit']);
}

if (isset($_POST['get_sms_data'])) {

    $code = Input::get('code');

    if ( ! $code)
        Output::json(['error' => '#1']);

    $table = '<div class="sms-flags-sep"></div>';
    $table .= '<table class="table table-striped table-hover">';
    $table .= '<thead>
    <tr>
        <th>' . Language::_('SMS tekstas') . '</th>
        <th style="text-align: center;">' . Language::_('Numeris') . '</th>
        <th style="text-align: center;">' . Language::_('Kaina') . '</th>
        <th style="text-align: center;">' . Language::_('Šalis') . '</th>
        <th style="text-align: center;">' . Language::_('Taškai') . '</th>
    </tr>
</thead>
<tbody>';
    $sms = simplexml_load_file(CONFIG_PATH . '/xml/paysera-sms.xml');
    foreach ($sms as $item) {

        $country = (string)$item->country;

        if (strtoupper($country) != strtoupper($code)) continue;

        $table .= '<tr>';
        $table .= '<td>' . (string)$item->keyword . ' ' . Session::get('donate_user_code') . '</td>';
        $table .= '<td style="text-align: center;">' . (string)$item->number . '</td>';
        $table .= '<td style="text-align: center;">' . (string)$item->price . ' ' . (string)$item->currency  .  '</td>';
        $table .= '<td style="text-align: center;">' . strtoupper((string)$item->country) . '</td>';
        $table .= '<td style="text-align: center;">' . (string)$item->points . '</td>';
        $table .= '</tr>';

    }
    $table .= '</tbody></table>';

    Output::json(['success' => 'ok', 'table' => $table]);
}

if (isset($_POST['settings_save'])) {
    $email = Input::get('email');
    $oldPassword = Input::get('old_password');
    $newPassword = Input::get('new_password');
    $server = Session::get('active_server_id');
    $username = Session::get('server_account_login');
    $update = false;
    $emailVerify = false;
    $emailForm = '';

    // change email
    if ( ! empty($email)) {
        $emailValidationPattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

        if (preg_match($emailValidationPattern, $email) !== 1)
            Output::json('Neteisingai sudarytas el. pašto adresas');

        $emailExists = DB::first('SELECT * FROM users WHERE email = ? AND id != ?', [$email, Session::get('donate_user_id')]);
        if ($emailExists)
            Output::json(Language::_('Toks el. pašto adresas jau naudojamas'));

        if (Auth::user()->email != $email) {
            while(true) {
                $code = File::randomString(35);
                $result = DB::first('SELECT * FROM email_verify WHERE code = ?', [$code]);
                if ( ! $result)
                    break;
            }

            $result = DB::first('SELECT * FROM users WHERE id = :id', [
                ':id' => Session::get('donate_user_id')
            ]);

            DB::query('UPDATE users SET email_status = :email_status, email = :email WHERE id = :id', [
                ':id' => Session::get('donate_user_id'),
                ':email' => $email,
                ':email_status' => 2
            ]);

            DB::query('INSERT INTO email_verify SET user_id = :user_id, old_email = :old_email, new_email = :new_email, code = :code, start_date = :start_date', [
                ':user_id' => $result->id,
                ':old_email' => $result->email,
                ':new_email' => $email,
                ':code' => $code,
                ':start_date' => date('Y-m-d H:i:s')
            ]);

            $code = base64_encode($code);

            $verifyLink = Settings::get('app.base_url') . '/index.php/email/verify/' . $code;

            $message = Language::_('Jei tikrai norite patvirtinti el. pašto adresą %s puslapyje %s paspauskite žemiau esančią patvirtinimo nuorodą', [$email, Settings::get('app.base_url')]) . '<br />';
            $message .= $verifyLink;

            Mail::send($email, Language::_('El. pašto adreso patvirtinimas'), $message);

            $update = true;
            $emailVerify = true;
            $emailForm = '<input type="text" name="email" class="form-control" placeholder="' . Language::_('El. pašto adresas') . '" disabled="disabled" value="' . Auth::user()->email . '" />
                          <span class="input-group-addon email-not-verified">
                                <span
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="' . Language::_('El. pašto adresas laukia patvirtinimo') . '"
                                    class="glyphicon glyphicon-time"
                                    aria-hidden="true"></span>
                            </span>';
        }
    } else {
        if (Auth::user()->email && Auth::user()->email_status == 1)
            Output::json(Language::_('Būtina nurodyti el. pašto adresą'));
    }

    // change password
    if ($oldPassword) {
        if ( ! $newPassword)
            Output::json(Language::_('Būtina nurodyti naują slaptažodį'));

        $oldEncryptedPassword = L2::hash($oldPassword, Server::getHashType($server));
        $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ? AND ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $oldEncryptedPassword], 'server', $server, 'login');
        if ( ! $serverResults)
            Output::json(Language::_('Neteisingas senas slaptažodis'));

        if (Settings::get('app.settings.min_password') > 0 && strlen($newPassword) <= Settings::get('app.settings.min_password'))
            Output::json(Language::_('Minimalus slaptažodžio ilgis: %s simbolių (-ai)', [Settings::get('app.settings.min_password')]));

        $newEncryptedPassword = L2::hash($newPassword, Server::getHashType($server));

        DB::query('UPDATE ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ? WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$newEncryptedPassword, $username], 'server', $server, 'login');

        $update = true;
    }

    if ($update) {
        if ($emailVerify)
            Output::json(['content' => Language::_('Nustatymai išsaugoti, el. pašto patvirtinimo nuoroda išsiųsta Jums į el. paštą'), 'type' => 'success', 'verify-email' => 'ok', 'email_form' => $emailForm]);
        else
            Output::json(['content' => Language::_('Nustatymai išsaugoti'), 'type' => 'success']);
    } else {
        Output::json(Language::_('Nėra nustatymų kuriuos reikėtų išsaugoti'));
    }
}

if (isset($_POST['logout'])) {
    Auth::logout();

    return Output::json(['success' => 'ok']);
}
