<?php

$id = (isset($_GET['id']) && ! empty($_GET['id'])) ? $_GET['id'] : false;
$action = (isset($_GET['action']) && ! empty($_GET['action'])) ? $_GET['action'] : false;

if ( ! $id || ! $action) exit('recovery error #1');

define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . '/libs/helpers/load.php');

if ($id == 'recovery' && $action == 'verify') {
    $code = (isset($_GET['r']) && ! empty($_GET['r'])) ? $_GET['r'] : false;
    $code = base64_decode($code);
    
    $result = DB::first('SELECT * FROM recovery WHERE code = :code', [
        ':code' => $code
    ]);
    if ( ! isset($result->id) || $result->active_until < time()) {
        Session::put('recovery-not-verified', Language::_('Slaptažodžio pakeitimas nepatvirtinas, jei nenumanote kodėl tai galėjo įvykti, susisiekite su administratoriumi'));
        URL::to('/#recovery');
    }
    $userId = $result->user_id;
    $server = $result->server;

    $result = DB::first('SELECT * FROM users WHERE id = :user_id', [
        ':user_id' => $userId
    ]);
    if ( ! isset($result->id)) {
        Session::put('recovery-not-verified', Language::_('Slaptažodžio pakeitimas nepatvirtinas, jei nenumanote kodėl tai galėjo įvykti, susisiekite su administratoriumi'));
        URL::to('/#recovery');
    }
    $username = $result->username;
    $email = $result->email;

    $result = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');
    
    $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));

    if ( ! isset($result->$loginFieldName)) {
        Session::put('recovery-not-verified', Language::_('Slaptažodžio pakeitimas nepatvirtinas, jei nenumanote kodėl tai galėjo įvykti, susisiekite su administratoriumi'));
        URL::to('/#recovery');
    }

    $password = File::randomString(6);
    $newPassword = L2::hash($password, Server::getHashType($server));

    DB::query('DELETE FROM recovery WHERE code = :code', [':code' => $code]);
    DB::query('UPDATE ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ? WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$newPassword, $username], 'server', $server, 'login');

    $subject = 'Naujas slaptažodis';

    $headers = "From: " . strip_tags(Settings::get('app.email')) . "\r\n";
    $headers .= "Reply-To: ". strip_tags(Settings::get('app.email')) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $message = 'Jūsų naujasis slaptažodis yra: ' . $password;

    mail($email, $subject, $message, $headers);

    Session::put('recovery-verified', Language::_('Slaptažodžio pakeitimas patvirtinas sėkmingai, naujas slaptažodis išsiųstas į Jūsų el. paštą.'));

    URL::to('/#recovery');
}