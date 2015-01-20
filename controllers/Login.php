<?php

class Login {
    public function index() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = Settings::get('database.servers');
        
        View::make('login', ['servers' => $servers]);
    }
}

