<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Router;

Router::get('/', 'Main@get_index');

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
Router::post('/user/token/reload', 'User@post_token');

// player
Router::ajax('/player', 'User@get_player');
Router::post('/player/change-name', 'User@post_changeName');
Router::post('/player/unstuck', 'User@post_unstuck');
Router::post('/player/level', 'User@post_level');

// shop
Router::ajax('/shop', 'Shop@get_index');
Router::post('/shop/buy', 'Shop@post_buy');

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
Router::post('/setup/check/mysql', 'Install@post_checkDBConnection');
Router::post('/setup/check/chmod', 'Install@post_checkChmod');
Router::post('/setup/start', 'Install@post_startInstall');

// payment
Router::post('/payment/notify/paypal', 'Balance@post_paypalCallback');
Router::post('/payment/notify/paysera-bank', 'Balance@post_payseraCallback');
Router::post('/payment/notify/paysera-sms', 'Balance@post_payseraSmsCallback');
Router::get('/payment/notify/paygol', 'Balance@post_paygolCallback');

Router::post('/payment/(:any)/(:any)', 'Balance@get_message');
Router::get('/payment/(:any)/(:any)', 'Balance@get_message');

// information
Router::get('/information', 'Information@get_index');

// verify
Router::get('/verify/email/(:any)', 'User@get_verifyEmail');
Router::get('/verify/recovery/(:any)', 'User@get_verifyRecovery');

// admin
Router::get('/admin', 'Donate\Controller\Admin\Main@get_index');

// users
Router::get('/admin/user', 'Donate\Controller\Admin\User@get_index');
Router::get('/admin/user/(:num)', 'Donate\Controller\Admin\User@get_index');

// configs
Router::get('/admin/config', 'Donate\Controller\Admin\Config@get_index');
Router::get('/admin/config/(:any)', 'Donate\Controller\Admin\Config@get');
Router::post('/admin/config/(:any)/save', 'Donate\Controller\Admin\Config@post');

// shop
Router::get('/admin/shop', 'Donate\Controller\Admin\Shop@get_index');
Router::get('/admin/shop/(:num)', 'Donate\Controller\Admin\Shop@get_index');
Router::get('/admin/shop/item', 'Donate\Controller\Admin\Shop@get_form');
Router::get('/admin/shop/item-group', 'Donate\Controller\Admin\Shop@get_groupForm');
Router::get('/admin/shop/item/(:num)', 'Donate\Controller\Admin\Shop@get_form');
Router::get('/admin/shop/item-group/(:num)', 'Donate\Controller\Admin\Shop@get_groupForm');
Router::get('/admin/shop/item/delete/(:num)', 'Donate\Controller\Admin\Shop@get_delete');
Router::get('/admin/shop/item/delete-group/(:num)', 'Donate\Controller\Admin\Shop@get_deleteGroup');
Router::post('/admin/shop/item', 'Donate\Controller\Admin\Shop@post_item');
Router::post('/admin/shop/item-group', 'Donate\Controller\Admin\Shop@post_itemGroup');

// sms keywords
Router::get('/admin/sms-keywords', 'Donate\Controller\Admin\SmsKeywords@get_index');
Router::get('/admin/sms-keywords/(:num)', 'Donate\Controller\Admin\SmsKeywords@get_index');
Router::get('/admin/sms-keywords/keyword', 'Donate\Controller\Admin\SmsKeywords@get_form');
Router::get('/admin/sms-keywords/keyword/(:num)', 'Donate\Controller\Admin\SmsKeywords@get_form');
Router::get('/admin/sms-keywords/keyword/delete/(:num)', 'Donate\Controller\Admin\SmsKeywords@get_delete');
Router::post('/admin/sms-keywords/keyword', 'Donate\Controller\Admin\SmsKeywords@post_item');

// translations
Router::get('/admin/translation', 'Donate\Controller\Admin\Translation@get_index');
Router::get('/admin/translation/add', 'Donate\Controller\Admin\Translation@get_new');
Router::get('/admin/translation/delete/(:any)', 'Donate\Controller\Admin\Translation@get_delete');
Router::get('/admin/translation/(:any)', 'Donate\Controller\Admin\Translation@get_translation');
Router::post('/admin/translation/save', 'Donate\Controller\Admin\Translation@post_save');
Router::post('/admin/translation/add', 'Donate\Controller\Admin\Translation@post_add');

// logs
Router::get('/admin/logs', 'Donate\Controller\Admin\Log@get_index');
Router::get('/admin/logs/(:num)', 'Donate\Controller\Admin\Log@get_index');

// stats
Router::get('/admin/stats', 'Donate\Controller\Admin\Stat@get_index');