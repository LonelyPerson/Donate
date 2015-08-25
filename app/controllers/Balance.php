<?php

class Balance {
    public function get_index() {
        // order / item number
        $itemNumber = md5(time());
        $pgItemNumber = md5(time() . 'pg' . time());

        // paysera
        try {
            $request = WebToPay::buildRequest(array(
                'projectid'     => Settings::get('app.mokejimai.id'),
                'sign_password' => Settings::get('app.mokejimai.secret'),
                'amount'        => 0,
                'orderid'       => $itemNumber,
                'paytext'	    => Settings::get('app.mokejimai.text') . ' (nr. [order_nr]) ([site_name])',
                'accepturl'     => Settings::get('app.base_url') . '/index.php/paysera-bank/verified',
                'cancelurl'     => Settings::get('app.base_url') . '/index.php/paysera-bank/cancel',
                'callbackurl'   => Settings::get('app.base_url') . '/index.php/paysera-bank/notify',
                'version'	    => Settings::get('app.mokejimai.version'),
                'test'          => Settings::get('app.mokejimai.test')
            ));
        } catch (WebToPayException $e) {
            echo $e->getMessage();
        }

        // paysera sms
        $sms = simplexml_load_file(CONFIG_PATH . '/xml/paysera-sms.xml');
        $payseraSms = [];
        foreach($sms as $item) {
            $country = (string)$item->country;

            if ( ! isset($payseraSms[country]))
                $payseraSms[$country] = $item;
        }

        return View::make('balance', ['itemNumber' => $itemNumber, 'hiddenInputs' => $request, 'payseraSms' => $payseraSms, 'pgItemNumber' => $pgItemNumber]);
    }

    public function post_paypal() {
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

    public function post_paysera() {
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

    public function post_paygol() {
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

    public function post_smsData() {
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
}
