<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\URL;

class Output {
    public static function json($content = array()) {
        header('Content-Type: application/json');
        
        if (is_array($content))
            echo json_encode($content);
        else
            echo json_encode(array('content' => $content, 'type' => 'danger'));
        
        exit;
    }

    public static function information($content = '') {
        Session::put('message', $content);
        URL::to('#information');
    }
    
    public static function formResponse() {
        if (Session::has('message')) {
            $type = Session::pull('type');
            $message = Session::pull('message');

            return '<div class="alert alert-' . $type . ' form-response">' . $message . '</div>';
        }

        return '';
    }
}