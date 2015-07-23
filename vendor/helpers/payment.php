<?php

$matches = URL::segments($_SERVER['PATH_INFO']);

$id = (isset($matches[0])) ? $matches[0] : false;
$action = (isset($matches[1])) ? $matches[1] : false;

$activePaymentMethods = ['paypal', 'paysera-bank', 'paysera-sms', 'paygol'];

switch ($id) {
    // payments
    case 'paypal':
        if ($action == 'notify')
            include_once VENDOR_PATH . '/helpers/paypal.php';
        break;
    case 'paysera-bank':
        if ($action == 'notify')
            include_once VENDOR_PATH . '/helpers/paysera_bank.php';
        break;
    case 'paysera-sms':
        if ($action == 'notify')
            include_once VENDOR_PATH . '/helpers/paysera_sms.php';
        break;
    case 'paygol':
        if ($action == 'notify')
            include_once VENDOR_PATH . '/helpers/paygol.php';
        break;
    // recovery
    case 'recovery':
        if ($action == 'verify')
            include_once VENDOR_PATH . '/helpers/recovery.php';
        break;
    // change email
    case 'email':
        if ($action == 'verify')
            include_once VENDOR_PATH . '/helpers/email_verify.php';
        break;
}

if (in_array($id, $activePaymentMethods) && ($action == 'verified' || $action == 'cancel')) {
    switch ($id) {
        case 'paypal':
            if ($action == 'verified')
                Output::information(Language::_('Jūsų apmokėjimas per paypal sistemą užskaitytas'));
            else
                Output::information(Language::_('Jūs atšaukėte apmokėjimą'));
            break;
        case 'paysera-bank':
            if ($action == 'verified')
                Output::information(Language::_('Jūsų apmokėjimas per mokejimai.lt sistemą užskaitytas'));
            else
                Output::information(Language::_('Jūs atšaukėte apmokėjimą'));
            break;
        case 'paygol':
            if ($action == 'verified')
                Output::information(Language::_('Jūsų apmokėjimas per Paygol sistemą užskaitytas'));
            else
                Output::information(Language::_('Jūs atšaukėte apmokėjimą'));
            break;
    }

}
