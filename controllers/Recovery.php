<?php

class Recovery {
    public function index() {
        if (Auth::isLoggedIn())
            Auth::logout();

        $servers = Settings::get('database.servers');

        View::make('recovery', ['servers' => $servers]);
    }
}