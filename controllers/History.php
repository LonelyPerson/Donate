<?php

class History {
    public function index() {
        $history = DB::get('SELECT * FROM history WHERE user_id = :user_id ORDER BY action_date DESC LIMIT 0,' . Settings::get('app.history.limit'), [
            ':user_id' => Session::get('donate_user_id')
        ]);
        
        return View::make('history', ['history' => $history]);
    }
}