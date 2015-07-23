<?php

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