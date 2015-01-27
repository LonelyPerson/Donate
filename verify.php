<?php

$id = (isset($_GET['id']) && ! empty($_GET['id'])) ? $_GET['id'] : false;
$action = (isset($_GET['action']) && ! empty($_GET['action'])) ? $_GET['action'] : false;

if ( ! $id || ! $action) exit('Verify error #1');

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/libs/helpers/load.php');

if ($id == 'email' && $action == 'verify') {
	$code = (isset($_GET['r']) && ! empty($_GET['r'])) ? $_GET['r'] : false;
    $code = base64_decode($code);

    $result = DB::first('SELECT * FROM email_verify WHERE code = :code', [
        ':code' => $code
    ]);
    if ( ! isset($result->id)) {
        exit('Verify error #2');
    }
    $userId = $result->user_id;

    $result = DB::first('SELECT * FROM users WHERE id = :user_id', [
        ':user_id' => $userId
    ]);
    if ( ! isset($result->id)) {
        exit('Verify error #3');
    }

    DB::query('DELETE FROM email_verify WHERE code = :code', [':code' => $code]);
    DB::query('UPDATE users SET email_status = :email_status WHERE id = :id', [
    	':email_status' => 1,
    	':id' => $userId
    ]);

    URL::to('/#login');
}