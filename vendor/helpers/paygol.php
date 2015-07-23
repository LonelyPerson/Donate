<?php

if ( ! Settings::get('app.paygol.enabled')) 
    exit('Paygol disabled');

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