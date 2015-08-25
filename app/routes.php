<?php

Router::get('/', 'Main@index');

// auth
Router::ajax('/login', 'User@get_login');
Router::post('/login', 'User@post_login');

Router::ajax('/registration', 'User@get_registration');
Router::post('/registration', 'User@post_registration');

Router::ajax('/recovery', 'User@get_recovery');
Router::post('/recovery', 'User@post_recovery');

// user
Router::ajax('/user', 'User@get_characters');
Router::post('/user/character/select', 'User@post_selectCharacter');
Router::post('/user/logout', 'User@post_logout');
Router::post('/user/online', 'User@post_isOnline');

// shop
Router::ajax('/shop', 'Shop@get_index');
Router::post('/shop/buy', 'Shop@post_buy');

// market
Router::ajax('/market', 'Market@get_index');
Router::post('/market/buy', 'Market@post_buy');

// inventory
Router::ajax('/inventory', 'Inventory@get_index');
Router::post('/inventory/delete', 'Inventory@post_delete');

// balance
Router::ajax('/balance', 'Balance@get_index');
Router::post('/balance/paypal', 'Balance@post_paypal');
Router::post('/balance/paysera', 'Balance@post_paysera');
Router::post('/balance/paygol', 'Balance@post_paygol');
Router::post('/balance/sms', 'Balance@post_smsData');

// history
Router::ajax('/history', 'History@get_index');

// settings
Router::ajax('/config', 'Config@get_index');
Router::post('/config/save', 'Config@post_save');

// language
Router::post('/language', 'Lang@post_language');

// install
Router::post('/install/check/mysql', 'Install@post_checkDBConnection');
Router::post('/install/check/chmod', 'Install@post_checkChmod');
Router::post('/install/start', 'Install@post_startInstall');
