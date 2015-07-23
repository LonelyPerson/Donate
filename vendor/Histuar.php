<?php

class Histuar {
    public static function add($key, $value, $userID = false) {
        DB::query('INSERT INTO history SET user_id = :user_id, action_key = :action_key, action_value = :action_value, action_date = :action_date', [
            ':user_id' => ($userID) ? $userID : Session::get('donate_user_id'),
            ':action_key' => $key,
            ':action_value' => $value,
            ':action_date' => date('Y-m-d H:i:s')
        ]);
    }
}