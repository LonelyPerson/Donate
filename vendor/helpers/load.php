<?php

use \Donate\Vendor\Settings;
use \Donate\Vendor\Language;
use \Donate\Vendor\Router;

if ( ! defined('STARTED')) {
    header('Content-Type: text/html; charset=utf-8');
    define('STARTED', true);
    error_reporting(E_ALL & ~E_NOTICE);

    // some global settings
    if ( ! defined('ROOT_PATH'))
        define('ROOT_PATH', dirname(__FILE__));

    define('APP_PATH', ROOT_PATH . '/app');
    define('VIEWS_PATH', APP_PATH . '/views');
    define('VENDOR_PATH', ROOT_PATH . '/vendor');
    define('CONTROLLERS_PATH', APP_PATH . '/controllers');
    define('CONFIG_PATH', APP_PATH . '/config');
    define('STORAGE_PATH', APP_PATH . '/storage');
    define('LANGUAGES_PATH', APP_PATH . '/languages');

    session_start();

    // autoload
    include VENDOR_PATH . '/autoload.php';

    include VENDOR_PATH . '/helpers/alias.php';
    include VENDOR_PATH . '/helpers/recaptchalib.php';

    // load settings
    Settings::load();

    if (config('app.timezone'))
        date_default_timezone_set(config('app.timezone'));

    Language::load();

    // installed?
    if ( ! file_exists(STORAGE_PATH . '/installed') && ! Request::isMethod('post')) {
        include ROOT_PATH . '/install/index.php';
        exit;
    }

    include_once APP_PATH . '/routes.php';

    Router::dispatch();
}
