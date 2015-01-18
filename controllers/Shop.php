<?php

class Shop {
    public function index() {
        $items = simplexml_load_file(ROOT_PATH . '/settings/xml/shop.xml');
        
        return View::make('shop', ['items' => $items]);
    }
}
