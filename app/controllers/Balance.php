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
use \Donate\Vendor\URL;

class Balance {
    public function get_index() {
        // order / item number
        $itemNumber = md5(time());
        $pgItemNumber = md5(time() . 'pg' . time());
        $server = Session::get('active_server_id');

        // paysera
        try {
            $request = \WebToPay::buildRequest(array(
                'projectid'     => config('app.mokejimai.id'),
                'sign_password' => config('app.mokejimai.secret'),
                'amount'        => 0,
                'orderid'       => $itemNumber,
                'paytext'	    => config('app.mokejimai.text') . ' (nr. [order_nr]) ([site_name])',
                'accepturl'     => config('app.base_url') . '/paysera-bank/verified',
                'cancelurl'     => config('app.base_url') . '/paysera-bank/cancel',
                'callbackurl'   => config('app.base_url') . '/notify/paysera-bank',
                'version'	    => config('app.mokejimai.version'),
                'test'          => config('app.mokejimai.test')
            ));
        } catch (WebToPayException $e) {
            echo $e->getMessage();
        }

        // paysera sms
        $sms = DB::get('SELECT * FROM sms_keywords WHERE server = ?', [$server]);
        $payseraSms = [];
        foreach($sms as $item) {
            $country = (string)$item->country;

            if ( ! isset($payseraSms[$country]))
                $payseraSms[$country] = $item;
        }

        return view('balance', ['itemNumber' => $itemNumber, 'hiddenInputs' => $request, 'payseraSms' => $payseraSms, 'pgItemNumber' => $pgItemNumber]);
    }

    public function post_paypal() {
        $data = $_POST;

        if ( ! Input::get('item_number') || ! Input::get('sum'))
            return Output::json(Language::_('Please enter DC quantity'));

        $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('sum'));
        if ( ! $number)
            return Output::json(Language::_('Please enter only numbers'));

        if (Input::get('sum') < Settings::get('app.paypal.min'))
            return Output::json(Language::_('Min. DC quantity are %s', [Settings::get('app.paypal.min')]));

        if (Settings::get('app.paypal.max') && Input::get('sum') > Settings::get('app.paypal.max'))
            return Output::json(Language::_('Max. DC quantity are %s', [Settings::get('app.paypal.max')]));

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

        $querystring .= "return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/payment/paypal/verified')) . "&";
        $querystring .= "cancel_return=" . urlencode(stripslashes(Settings::get('app.base_url') . '/payment/paypal/cancel')) . "&";
        $querystring .= "notify_url=" . urlencode(Settings::get('app.base_url') . '/payment/notify/paypal');

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

        _log('Initiated DC replenish through paypal', 'user');

        if (Settings::get('app.paypal.test')) {
            return Output::json(['redirect' => 'https://www.sandbox.paypal.com/cgi-bin/webscr' . $querystring]);
        } else {
            return Output::json(['redirect' => 'https://www.paypal.com/cgi-bin/webscr' . $querystring]);
        }
    }
    public function post_paypalCallback() {
        $itemNumber = (isset($_POST['item_number']) && ! empty($_POST['item_number'])) ? $_POST['item_number'] : false;

        if ( ! $itemNumber)
            exit('paypal error #1');

        $result = DB::first("SELECT * FROM paypal WHERE item_number = ?", [$itemNumber]);
        if ( ! isset($result->item_number))
            exit('paypal error #2');

        $verified = Paypal::verify();

        if ($verified) {
            $result = DB::first("SELECT * FROM paypal WHERE item_number = ?", [$itemNumber]);

            if (isset($result->item_number)) {
                if ($_POST['mc_gross'] != $result->amount) {
                    DB::query("UPDATE paypal SET status = 'error #3' WHERE item_number = ?", [$itemNumber]);
                    exit(0);
                }

                $txnData = DB::first("SELECT * FROM paypal WHERE txn_id = ?", [$_POST['txn_id']]);
                if (isset($txnData->item_number)) {
                    DB::query("UPDATE paypal SET status = 'error #4' WHERE item_number = ?", [$itemNumber]);
                    exit(0);
                }

                $buyerInfo = json_encode($_POST);

                $userData = DB::first("SELECT * FROM users WHERE id = ?", [$result->user_id]);
                $newPoints = $userData->balance + $result->points;

                DB::query("UPDATE users SET balance = :balance WHERE id = :id", [':balance' => $newPoints, ':id' => $userData->id]);
                DB::query("UPDATE paypal SET
                        status = :status,
                        txn_id = :txn_id,
                        buyer_info = :buyer_info,
                        end_date = :end_date
                        WHERE item_number = :item_number", [
                            ':status' => 'ok',
                            ':txn_id' => $_POST['txn_id'],
                            ':buyer_info' => $buyerInfo,
                            ':end_date' => date('Y-m-d H:i:s'),
                            ':item_number' => $itemNumber
                        ]);

                _log('Successfully added <strong>' . $result->points . '</strong> DC to balance through paypal', 'user', $userData->username);

                Histuar::add(Language::_('Balansas'), Language::_('%s DC successfully added to your account', [$result->points]), $userData->id);

                exit ('ok');
            }
        }
    }

    public function post_paysera() {
        $data = $_POST;

        if ( ! Input::get('order') || ! Input::get('sum'))
            return Output::json(Language::_('Please enter DC quantity'));

        $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('sum'));
        if ( ! $number)
            return Output::json(Language::_('Please enter only numbers'));

        if (Input::get('sum') < Settings::get('app.mokejimai.min'))
            return Output::json(Language::_('Min. DC quantity are %s', [Settings::get('app.mokejimai.min')]));

        if (Settings::get('app.mokejimai.max') && Input::get('sum') > Settings::get('app.mokejimai.max'))
            return Output::json(Language::_('Max. DC quantity are %s', [Settings::get('app.mokejimai.max')]));

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
            $request = \WebToPay::buildRequest(array(
                'projectid'     => Settings::get('app.mokejimai.id'),
                'sign_password' => Settings::get('app.mokejimai.secret'),
                'amount'        => $amount,
                'orderid'       => $order,
                'currency'      => strtoupper(Settings::get('app.mokejimai.currency')),
                'paytext'       => Settings::get('app.mokejimai.text') . ' (nr. [order_nr]) ([site_name])',
                'accepturl'     => Settings::get('app.base_url') . '/payment/paysera-bank/verified',
                'cancelurl'     => Settings::get('app.base_url') . '/payment/paysera-bank/cancel',
                'callbackurl'   => Settings::get('app.base_url') . '/payment/notify/paysera-bank',
                'version'       => Settings::get('app.mokejimai.version'),
                'test'          => Settings::get('app.mokejimai.test')
            ));
        } catch (WebToPayException $e) {
            Output::json($e->getMessage());
        }

        _log('Initiated DC replenish through paysera', 'user');

        return Output::json(['submit' => 'submit', 'sign' => $request['sign'], 'data' => $request['data']]);
    }
    public function post_payseraCallback() {
        try  {
            $response = WebToPay::validateAndParseData($_POST, Settings::get('app.mokejimai.id'), Settings::get('app.mokejimai.secret'));
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        $orderid = $response['orderid'];

        $result = DB::first("SELECT * FROM mokejimai WHERE orderid = :orderid", [':orderid' => $orderid]);

        if ( ! isset($result->orderid)) exit(0);

        $userData = DB::first("SELECT * FROM users WHERE id = :id", [':id' => $result->user_id]);

        $newPoints = round($userData->balance + $result->points, 2);

        DB::query("UPDATE users SET balance = :balance WHERE id = :id", [':balance' => $newPoints, ':id' => $userData->id]);

        DB::query('UPDATE mokejimai SET status = :status, end_date = :end_date WHERE orderid = :orderid', [
            ':status' => 'ok',
            ':end_date' => date('Y-m-d H:i:s'),
            ':orderid' => $orderid
        ]);

        Histuar::add(Language::_('Balansas'), Language::_('%s DC successfully added to your account', [$result->points]), $userData->id);

        _log('Successfully added <strong>' . $result->points . '</strong> DC through paysera', 'user', $userData->username);

        exit('OK');
    }
    public function post_payseraSmsCallback() {
        $server = Session::get('active_server_id');

        try  {
            $response = WebToPay::validateAndParseData($_POST, Settings::get('app.mokejimai.id'), Settings::get('app.mokejimai.secret'));
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        $result = DB::first('SELECT * FROM sms WHERE sms_type = :sms_type AND sms_unique_id = :sms_unique_id', [
            ':sms_type' => 'paysera',
            ':sms_unique_id' => $response['id']
        ]);

        if ($result)
            exit('ERROR paysera sms #1');

        if ($response['projectid'] != Settings::get('app.mokejimai.id'))
            exit('ERROR paysera sms #2');

        if ($response['version'] != Settings::get('app.mokejimai.version'))
            exit('ERROR paysera sms #3');

        $result = DB::first('SELECT * FROM sms_keywords WHERE server = ? AND keyword = ?', [$server, strtolower($response['key'])]);
        if ( ! isset($result->id))
            exit('ERROR paysera sms #4');

        $result = $nodes[0];

        $ex = explode(' ', $response['sms']);
        if ( ! isset($ex[0], $ex[1]) || empty($ex[0]) || empty($ex[1]))
            exit('ERROR paysera sms #5');

        $userCode = end($ex);

        $userData = DB::first("SELECT * FROM users WHERE code = :code", [':code' => $userCode]);
        if ( ! $userData)
            exit('ERROR paysera sms #6');

        $newPoints = round($userData->balance + $result->points, 2);

        DB::query("UPDATE users SET balance = :balance WHERE code = :code", [':balance' => $newPoints, ':code' => $userCode]);

        DB::query('INSERT INTO sms SET sms_unique_id = :sms_unique_id, sms_keyword = :sms_keyword, sms_price = :sms_price, sms_currency = :sms_currency, sms_response = :sms_response, sms_date = :sms_date, sms_type = :sms_type, sms_from = :sms_from', [
            ':sms_unique_id' => $response['id'],
            ':sms_keyword' => $response['sms'],
            ':sms_price' => $response['price'],
            ':sms_currency' => $response['currency'],
            ':sms_response' => json_encode($response),
            ':sms_date' => date('Y-m-d H:i:s'),
            ':sms_type' => 'paysera',
            ':sms_from' => $response['from']
        ]);

        Histuar::add(Language::_('Balansas'), Language::_('%s DC successfully added to your account', [$result->points]), $userData->id);

        _log('Successfully added <strong>' . $result->points . '</strong> DC through paysera SMS', 'user', $userData->username);

        if (isset($result->response) && ! empty($result->response))
            echo 'OK ' . $result->response;
        else
            echo 'OK';
    }

    public function post_paygol() {
        $data = $_POST;

        if ( ! Input::get('order') || ! Input::get('pg_price'))
            return Output::json(Language::_('Please enter DC quantity'));

        $number = preg_match("/^-?(?:\d+|\d*\.\d+)$/", Input::get('pg_price'));
        if ( ! $number)
            return Output::json(Language::_('Please enter only numbers'));

        if (Input::get('pg_price') < Settings::get('app.paygol.min'))
            return Output::json(Language::_('Min. DC quantity are %s', [Settings::get('app.paygol.min')]));

        if (Settings::get('app.paygol.max') && Input::get('pg_price') > Settings::get('app.paygol.max'))
            return Output::json(Language::_('Max. DC quantity are %s', [Settings::get('app.paygol.max')]));

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

        _log('Initiated DC replenish through paygol', 'user');

        return Output::json(['submit' => 'submit']);
    }
    public function post_paygolCallback() {
        // check that the request comes from PayGol server
        if ( ! in_array($_SERVER['REMOTE_ADDR'], array('109.70.3.48', '109.70.3.146', '109.70.3.210'))) {
            header("HTTP/1.0 403 Forbidden");
            die("ERROR paygol #1");
        }

        $q = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

        $ex = explode('&', $q);
        $q = [];
        foreach ($ex as $e) {
            $ex = explode('=', $e);

            $q[$ex[0]] = $ex[1];
        }

        // get the variables from PayGol system
        $message_id = $q['message_id'];
        $service_id = $q['service_id'];
        $sender = $q['sender'];
        $operator   = $q['operator'];
        $country    = $q['country'];
        $orderid = $q['custom'];
        $points = $q['points'];
        $price  = $q['price'];
        $currency = $q['currency'];
        $method = $q['method'];

        $result = DB::first("SELECT * FROM paygol WHERE orderid = :orderid", [':orderid' => $orderid]);
        if ( ! isset($result->orderid)) 
            exit('ERROR paygol #2');

        $userData = DB::first("SELECT * FROM users WHERE id = :id", [':id' => $result->user_id]);
        if ( ! isset($userData->id))
            exit('ERROR paygol #3'); 

        $newPoints = round($userData->balance + $result->points, 2);

        DB::query("UPDATE users SET balance = :balance WHERE id = :id", [':balance' => $newPoints, ':id' => $userData->id]);
        DB::query('UPDATE paygol SET sender_number = :sender_number, operator = :operator, country = :country, pay_method = :pay_method, status = :status, end_date = :end_date WHERE orderid = :orderid', [
            ':sender_number' => $sender,
            ':operator' => $operator,
            ':country' => $country,
            ':pay_method' => $method,
            ':status' => 'ok',
            ':end_date' => date('Y-m-d H:i:s'),
            ':orderid' => $orderid
        ]);

        Histuar::add(Language::_('Balansas'), Language::_('%s DC successfully added to your account', [$result->points]), $userData->id);

        _log('Successfully added <strong>' . $result->points . '</strong> DC through paygol', 'user');

        exit('OK');
    }

    public function post_smsData() {
        $code = Input::get('code');
        $server = Session::get('active_server_id');

        if ( ! $code)
            Output::json(['error' => '#1']);

        $table = '<div class="sms-flags-sep"></div>';
        $table .= '<table class="table table-striped table-hover">';
        $table .= '<thead>
        <tr>
            <th>' . Language::_('SMS tekstas') . '</th>
            <th style="text-align: center;">' . Language::_('Number') . '</th>
            <th style="text-align: center;">' . Language::_('Price') . '</th>
            <th style="text-align: center;">' . Language::_('Country') . '</th>
            <th style="text-align: center;">' . Language::_('DC') . '</th>
        </tr>
    </thead>
    <tbody>';
        $sms = DB::get('SELECT * FROM sms_keywords WHERE server = ?', [$server]);
        foreach ($sms as $item) {
            $country = (string)$item->country;

            if (strtoupper($country) != strtoupper($code)) continue;

            $table .= '<tr>';
            $table .= '<td>' . (string)$item->keyword . ' ' . Session::get('donate_user_code') . '</td>';
            $table .= '<td style="text-align: center;">' . (string)$item->phone . '</td>';
            $table .= '<td style="text-align: center;">' . (string)$item->price . ' ' . (string)$item->currency  .  '</td>';
            $table .= '<td style="text-align: center;">' . strtoupper((string)$item->country) . '</td>';
            $table .= '<td style="text-align: center;">' . (string)$item->points . '</td>';
            $table .= '</tr>';

        }
        $table .= '</tbody></table>';

        Output::json(['success' => 'ok', 'table' => $table]);
    }

    public function get_message($params = '') {
        $ex = explode(',', $params);
        $id = $ex[0];
        $action = $ex[1];

        $activePaymentMethods = ['paypal', 'paysera-bank', 'paysera-sms', 'paygol'];

        if (in_array($id, $activePaymentMethods) && ($action == 'verified' || $action == 'cancel')) {
            switch ($id) {
                case 'paypal':
                    if ($action == 'verified')
                        Output::information(Language::_('Your payment through paypal was successfully'));
                    else
                        Output::information(Language::_('Your payment was cancelled'));
                    break;
                case 'paysera-bank':
                    if ($action == 'verified')
                        Output::information(Language::_('Your payment through paysera was successfully'));
                    else
                        Output::information(Language::_('Your payment was cancelled'));
                    break;
                case 'paygol':
                    if ($action == 'verified')
                        Output::information(Language::_('Your payment through paygol was successfully'));
                    else
                        Output::information(Language::_('Your payment was cancelled'));
                    break;
            }

        }
    }
}
