<?php

class User {
    public function index() {
        $characters = DB::get("SELECT * FROM " . SQL::get('sql.characters.characters') . " WHERE " . SQL::get('sql.characters.account_name') . " = ?", [Session::get('server_account_login')], 'server');
        
        return View::make('user', ['characters' => $characters]);
    }
}