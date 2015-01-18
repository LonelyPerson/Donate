<?php

class View {
    public static function make($template, $args = []) {
        extract($args);
        
        include ROOT_PATH . '/views/' . $template . '.view.php';
    }
}