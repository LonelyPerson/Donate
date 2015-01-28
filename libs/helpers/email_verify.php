<?php

$matches = URL::segments($_SERVER['PATH_INFO']);
$code = (isset($matches[2]) && ! empty($matches[2])) ? $matches[2] : false;

if ( ! $code)
	Output::information(Language::_('Nepavyko patvirtinti el. pašto adreso'));

$code = base64_decode($code);

$result = DB::first('SELECT * FROM email_verify WHERE code = :code', [
    ':code' => $code
]);
if ( ! isset($result->id))
	Output::information(Language::_('Nepavyko patvirtinti el. pašto adreso'));

$userId = $result->user_id;

$result = DB::first('SELECT * FROM users WHERE id = :user_id', [
    ':user_id' => $userId
]);
if ( ! isset($result->id)) 
   Output::information(Language::_('Nepavyko patvirtinti el. pašto adreso'));

DB::query('UPDATE email_verify SET end_date = :end_date WHERE code = :code', [':code' => $code, ':end_date' => date('Y-m-d H:i:s')]);
DB::query('UPDATE users SET email_status = :email_status WHERE id = :id', [
    ':email_status' => 1,
    ':id' => $userId
]);

Output::information(Language::_('El. paštas patvirtintas sėkmingai'));