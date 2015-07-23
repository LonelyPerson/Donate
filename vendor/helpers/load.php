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

    include VENDOR_PATH . '/PHPMailer/PHPMailer.php';
    include VENDOR_PATH . '/PHPMailer/SMTP.php';

    // autoload classes
    spl_autoload_register(function($className) {
        $namespace = str_replace("\\", "/", __NAMESPACE__);
        $className = str_replace("\\", "/", $className);

        $class = VENDOR_PATH . "/" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";

        include $class;
    });

    // load all settings
    Settings::load();

    include VENDOR_PATH . '/helpers/recaptchalib.php';

    if (Settings::get('app.timezone'))
        date_default_timezone_set(Settings::get('app.timezone'));

    Language::load();

    // ajax
    if (strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST')) !== false && ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
        include VENDOR_PATH . '/helpers/ajax.php';
    }

    // payment
    include VENDOR_PATH . '/helpers/payment.php';
}
