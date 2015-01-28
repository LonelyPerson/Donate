<?php

class Information {
    public function index() {
        $message = Session::get('message');
        
        Session::forget('message');
        
        return View::make('information', ['message' => $message]);
    }
}

