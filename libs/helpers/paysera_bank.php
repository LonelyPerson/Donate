<?php

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