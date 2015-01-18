<?php

class Auth {
    public static function check($username = '', $password = '', $server = 0) {
        $encodedPassword = L2::hash($password);
        $serverResults = DB::first('SELECT * FROM ' . Settings::get('sql.accounts.accounts') . ' WHERE ' . Settings::get('sql.accounts.login') . ' = ? AND ' . Settings::get('sql.accounts.password') . ' = ?', [$username, $encodedPassword], 'server', $server);
        
        $loginFieldName = Settings::get('sql.accounts.login');
        $login = $serverResults->$loginFieldName;
        
        if ($serverResults && isset($login) && ! empty($login)) {
            Session::put('server_user_login', $login);
            
            $results = DB::first('SELECT * FROM users WHERE username = ?', [$login]);
            if ( ! isset($results->id)) {
                $id = DB::lastInsertId('INSERT INTO users SET username = ?, server = ?', [$login, $server]);
            } else {
                $id = $results->id;
            }
            
            Session::put('server_account_login', $login);
            Session::put('donate_user_id', $id);
            Session::put('active_server_id', $server);
            
            return true;
        }
            
        return false;
    }
    
    public static function user() {
        $results = DB::first('SELECT * FROM users WHERE id = :id', [':id' => Session::get('donate_user_id')]);
        
        return $results;
    }
    
    public static function isLoggedIn() {
        if (Session::has('donate_user_id'))
            return true;
        
        return false;
    }
    
    public static function logout() {
        Session::destroy();
        
        return true;
    }
}