<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\URL;

class View {
    public static function make($template, $args = [], $save = false) {
        Session::put('back', URL::current());

        extract($args);

        if (file_exists(VIEWS_PATH . '/' . $template . '.view.php')) {
        	if ($save) {
                ob_start();
                include VIEWS_PATH . '/' . $template . '.view.php';
                $content = ob_get_clean();
        		return $content;
        	} else {
        		include VIEWS_PATH . '/' . $template . '.view.php';
        	}
        } else {
        	exit('Error loading "' . $template . '" view');
        }
    }
}
