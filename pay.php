<?php

$id = (isset($_GET['id']) && ! empty($_GET['id'])) ? $_GET['id'] : false;
$action = (isset($_GET['action']) && ! empty($_GET['action'])) ? $_GET['action'] : false;

if ( ! $id || ! $action) exit('pay error #1');

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/libs/helpers/load.php');

/* 
 * paypal 
 */
if ($id == 'paypal' && $action == 'notify') {
    if ( ! Settings::get('app.paypal.enabled')) exit('Paypal disabled');

    $itemNumber = (isset($_POST['item_number']) && ! empty($_POST['item_number'])) ? $_POST['item_number'] : false;
    
    if ( ! $itemNumber) exit('paypal error #1');
    
    $result = DB::first("SELECT * FROM paypal WHERE item_number = ?", [$itemNumber]);
    if ( ! isset($result->item_number)) exit('paypal error #2');

    $verified = Paypal::verify();

    if ($verified) {
        if ($_POST['payment_status'] != 'Completed') { 
            DB::query("UPDATE paypal SET status = 'error #2' WHERE item_number = ?", [$itemNumber]);
            exit(0); 
        }

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

            Histuar::add(Language::_('Balansas'), Language::_('Papildytas balansas per paypal sistemą. Papildymo suma: %s', [$result->points]), $userData->id);
            
            exit ('ok');
        }
    }
}

/*
 * mokejimai 
 */
if ($id == 'mokejimai' && $action == 'notify') {
    if ( ! Settings::get('app.mokejimai.enabled')) exit('Paysera disabled');

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
    
    Histuar::add(Language::_('Balansas'), Language::_('Papildytas balansas per mokejimai.lt sistemą. Papildymo suma: %s', [$result->points]), $userData->id);
    
    exit('OK');
}

if ($id == 'paysera-sms' && $action == 'notify') {
    if ( ! Settings::get('app.sms.paysera')) exit('Paysera sms disabled');

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

    $xml = simplexml_load_file(ROOT_PATH . '/settings/xml/paysera-sms.xml');
    $nodes = $xml->xpath('//sms/item/keyword[.="' . strtolower($response['key']) . '"]/parent::*');
    if ( ! $nodes || ! isset($nodes[0]) || empty($nodes[0]))
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

    Histuar::add(Language::_('Balansas'), Language::_('Papildytas balansas per mokejimai.lt sms sistemą. Papildymo suma: %s', [$result->points]), $userData->id);

    if (isset($result->response) && ! empty($result->response))
        echo 'OK ' . $result->response;
    else
        echo 'OK';
}

/*
 * paygol
 */
if ($id == 'paygol' && $action == 'notify') {
    if ( ! Settings::get('app.paygol.enabled')) exit('Paygol disabled');

    // check that the request comes from PayGol server
    if ( ! in_array($_SERVER['REMOTE_ADDR'], array('109.70.3.48', '109.70.3.146', '109.70.3.210'))) {
        header("HTTP/1.0 403 Forbidden");
        die("ERROR paygol #1");
    }

    // get the variables from PayGol system
    $message_id = $_GET['message_id'];
    $service_id = $_GET['service_id'];
    $sender = $_GET['sender'];
    $operator   = $_GET['operator'];
    $country    = $_GET['country'];
    $orderid = $_GET['custom'];
    $points = $_GET['points'];
    $price  = $_GET['price'];
    $currency = $_GET['currency'];
    $method = $_GET['method'];
    
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
    
    Histuar::add(Language::_('Balansas'), Language::_('Papildytas balansas per Paygol sistemą. Papildymo suma: %s', [$result->points]), $userData->id);
    
    exit('OK');
}

if (($id == 'paypal' || $id == 'mokejimai' || $id == 'paygol') && ($action == 'verify' || $action == 'cancel')) {
    Session::put('id', $id);
    Session::put('action', $action);
    
    URL::to('/#pay');
}