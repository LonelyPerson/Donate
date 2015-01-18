<?php

class User {
    public function index() {
        $characters = DB::get("SELECT * FROM " . Settings::get('sql.characters.characters') . " WHERE " . Settings::get('sql.characters.account_name') . " = ?", [Settings::get('server_account_login')], 'server');
        
        return View::make('user', ['characters' => $characters]);
    }
}