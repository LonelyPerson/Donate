<?php

class Paypal {
    private static $paypalSandboxUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    private static $paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';
    
    public static function verify() {
        if (Settings::get('app.paypal.test')) {
            $url = self::$paypalSandboxUrl;
        } else {
            $url = self::$paypalUrl;
        }
        
        $encodedData = 'cmd=_notify-validate';
         
        if ( ! empty($_POST)) {
            $encodedData .= '&' . file_get_contents('php://input');
        } else {
            throw new Exception("No POST data found.");
        }
        
        DB::query("UPDATE paypal SET status = ? WHERE item_number = ?", [$encoded_data,$_POST['item_number']]);

        $response = @file_get_contents($url . '/' . $encodedData);
        $response = trim($response);
        
        return ($response == 'VERIFIED') ? true : false;
    }
}
?>