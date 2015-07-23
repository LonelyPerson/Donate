<?php

class View {
    public static function make($template, $args = []) {
        extract($args);

        include VIEWS_PATH . '/' . $template . '.view.php';
    }
}
