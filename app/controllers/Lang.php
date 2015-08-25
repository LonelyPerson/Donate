<?php

class Lang {
    // set language
    public function post_language() {
        $language = Input::get('language');

        if ( ! empty($language))
            Session::put('active_language', $language);

        return Output::json(['success' => 'ok']);
    }
}
