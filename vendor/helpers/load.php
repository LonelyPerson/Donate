<?php

if ( ! defined('STARTED')) {
    define('STARTED', true);
    error_reporting(1);

    // some global settings
    if ( ! defined('ROOT_PATH'))
        define('ROOT_PATH', dirname(__FILE__));

    define('APP_PATH', ROOT_PATH . '/app');
    define('VIEWS_PATH', APP_PATH . '/views');
    define('VENDOR_PATH', ROOT_PATH . '/vendor');
    define('CONTROLLERS_PATH', APP_PATH . '/controllers');
    define('CONFIG_PATH', APP_PATH . '/config');
    define('STORAGE_PATH', APP_PATH . '/storage');

    include VENDOR_PATH . '/PHPMailer/PHPMailer.php';
    include VENDOR_PATH . '/PHPMailer/SMTP.php';

    session_start();

    // autoload
    include VENDOR_PATH . '/autoload.php';

    // load settings
    Settings::load();

    if (Settings::get('app.timezone'))
        date_default_timezone_set(Settings::get('app.timezone'));

    Language::load();

    include_once APP_PATH . '/routes.php';

    Router::dispatch();

    // payment
    include VENDOR_PATH . '/helpers/payment.php';

    // installed?
    if ( ! Settings::get('app.dev')) {
        if ( ! file_exists(STORAGE_PATH . '/installed')) {
            include ROOT_PATH . '/install/index.php';
            exit;
        }

        if (file_exists(STORAGE_PATH . '/installed') && file_exists(ROOT_PATH . '/install')) {
            include ROOT_PATH . '/install/warning.php';
            exit;
        }
    }
}
