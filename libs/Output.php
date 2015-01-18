<?php

class Output {
    public static function json($content = array()) {
        header('Content-Type: application/json');
        
        if (is_array($content))
            echo json_encode($content);
        else
            echo json_encode(array('content' => $content, 'type' => 'danger'));
        
        exit;
    }
}