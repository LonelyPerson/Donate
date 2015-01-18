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

if (($id == 'paypal' || $id == 'mokejimai') && ($action == 'verify' || $action == 'cancel')) {
    Session::put('id', $id);
    Session::put('action', $action);
    
    URL::to('/#pay');
}