<?php

if ( ! defined('STARTED')) {
    define('STARTED', true);
    error_reporting(1);

    // some global settings
    if ( ! defined('ROOT_PATH'))
        define('ROOT_PATH', dirname(__FILE__));

    include ROOT_PATH . '/libs/PHPMailer/PHPMailer.php';
    include ROOT_PATH . '/libs/PHPMailer/SMTP.php';

    // autoload classes
    spl_autoload_register(function($className) {
        $namespace = str_replace("\\", "/", __NAMESPACE__);
        $className = str_replace("\\", "/", $className);

        $class = ROOT_PATH . "/libs/" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";

        include $class;
    });

    // load all settings
    Settings::load();

    include ROOT_PATH . '/libs/helpers/recaptchalib.php';

    if (Settings::get('app.timezone'))
        date_default_timezone_set(Settings::get('app.timezone'));

    Language::load();
}
