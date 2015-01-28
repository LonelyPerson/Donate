<?php

class Balance {
    public function index() {
        // order / item number
        $itemNumber = md5(time());
        $pgItemNumber = md5(time() . 'pg' . time());
        
        // paysera
        try {
            $request = WebToPay::buildRequest(array(
                'projectid'     => Settings::get('app.mokejimai.id'),
                'sign_password' => Settings::get('app.mokejimai.secret'),
                'amount'        => 0,
                'orderid'       => $itemNumber,
                'paytext'	    => Settings::get('app.mokejimai.text') . ' (nr. [order_nr]) ([site_name])',
                'accepturl'     => Settings::get('app.base_url') . '/e.php/paysera-bank/verified',
                'cancelurl'     => Settings::get('app.base_url') . '/e.php/paysera-bank/cancel',
                'callbackurl'   => Settings::get('app.base_url') . '/e.php/paysera-bank/notify',
                'version'	    => Settings::get('app.mokejimai.version'),
                'test'          => Settings::get('app.mokejimai.test')
            ));
        } catch (WebToPayException $e) {
            echo $e->getMessage();
        }

        // paysera sms
        $sms = simplexml_load_file(ROOT_PATH . '/settings/xml/paysera-sms.xml');
        $payseraSms = [];
        foreach($sms as $item) {
            $country = (string)$item->country;

            if ( ! isset($payseraSms[country]))
                $payseraSms[$country] = $item;
        }
        
        return View::make('balance', ['itemNumber' => $itemNumber, 'hiddenInputs' => $request, 'payseraSms' => $payseraSms, 'pgItemNumber' => $pgItemNumber]);
    }
}