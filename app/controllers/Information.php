<?php

class Information {
    public function get_index() {
        $message = Session::get('message');

        Session::forget('message');

        return View::make('information', ['message' => $message]);
    }
}
