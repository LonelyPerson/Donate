<?php

class Pay {
    public function index() {
        $id = Session::get('id');
        $action = Session::get('action');
        
        Session::forget('id');
        Session::forget('action');
        
        return View::make('pay', ['id' => $id, 'action' => $action]);
    }
}

