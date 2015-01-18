<?php

class Registration {
    public function index() {
        if (Settings::get('app.registration.enabled') == false) 
            return View::make('login');
        
        $servers = Settings::get('database.servers');
        
        return View::make('registration', ['servers' => $servers]);
    }
}

