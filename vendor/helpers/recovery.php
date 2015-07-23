<?php

$matches = URL::segments($_SERVER['PATH_INFO']);
$code = (isset($matches[2]) && ! empty($matches[2])) ? $matches[2] : false;
$code = base64_decode($code);

if ( ! $code)
	Output::information(Language::_('Nepavyko patvirtinti slaptažodžio keitimo'));

$result = DB::first('SELECT * FROM recovery WHERE code = :code', [
    ':code' => $code
]);
if ( ! isset($result->id) || $result->active_until < time()) 
	Output::information(Language::_('Nepavyko patvirtinti slaptažodžio keitimo'));

$userId = $result->user_id;
$server = $result->server;

$result = DB::first('SELECT * FROM users WHERE id = :user_id', [
    ':user_id' => $userId
]);
if ( ! isset($result->id))
    Output::information(Language::_('Nepavyko patvirtinti slaptažodžio keitimo'));

$username = $result->username;
$email = $result->email;
$emailStatus = $result->email_status;

if ($emailStatus != 1)
	Output::information(Language::_('Nepavyko patvirtinti slaptažodžio keitimo'));

$result = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$username], 'server', $server, 'login');

$loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));

if ( ! isset($result->$loginFieldName)) 
	Output::information(Language::_('Nepavyko patvirtinti slaptažodžio keitimo'));

$password = File::randomString(6);
$newPassword = L2::hash($password, Server::getHashType($server));

DB::query('DELETE FROM recovery WHERE code = :code', [':code' => $code]);
DB::query('UPDATE ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' SET ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ? WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ?', [$newPassword, $username], 'server', $server, 'login');

$message = Language::_('Jūsų naujasis slaptažodis yra: %s', [$password]);

Mail::send($email, Language::_('Naujas slaptažodis'), $message);

Output::information(Language::_('Slaptažodžio keitimas patvirtinas sėkmingai, naujas slaptažodis išsiųstas į Jūsų el. paštą.'));