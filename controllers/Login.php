<?php

class Login {
    public function index() {
        $servers = Settings::get('database.servers');
        
        View::make('login', ['servers' => $servers]);
    }
}

