<?php

class Registration {
    public function index() {
        if (Settings::get('app.registration.enabled') == false) 
            return View::make('login');

        if (Auth::isLoggedIn())
            Auth::logout();
        
        $servers = Settings::get('database.servers');
        
        return View::make('registration', ['servers' => $servers]);
    }
}

