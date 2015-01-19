<?php

class Auth {
    public static function check($username = '', $password = '', $server = 0) {
        $encodedPassword = L2::hash($password, Server::getHashType($server));
        $serverResults = DB::first('SELECT * FROM ' . SQL::get('sql.accounts.accounts', Server::getID($server)) . ' WHERE ' . SQL::get('sql.accounts.login', Server::getID($server)) . ' = ? AND ' . SQL::get('sql.accounts.password', Server::getID($server)) . ' = ?', [$username, $encodedPassword], 'server', $server, 'login');
        
        $loginFieldName = SQL::get('sql.accounts.login', Server::getID($server));
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