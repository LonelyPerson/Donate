<?php

class DB {
    private static $donateDB = null;
    private static $serverDB = null;
    
    public static function connect($host, $user, $pass, $db) {
        try {
            return new PDO('mysql:dbname=' . $db . ';host=' . $host, $user, $pass, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ));
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function dbh($conn = 'donate', $serverID = 0) {
        if ($conn == 'donate' && is_null(self::$donateDB) === true) {
            $host = Settings::get('database.donate.host');
            $user = Settings::get('database.donate.user');
            $pass = Settings::get('database.donate.password');
            $db = Settings::get('database.donate.db');

            self::$donateDB = self::connect($host, $user, $pass, $db);
        }
        
        if ($conn == 'server' && is_null(self::$serverDB) === true) {
            if (Auth::isLoggedIn()) $serverID = Session::get('active_server_id');
            
            $serverData = Settings::get('database.servers');

            $host = $serverData[$serverID]['host'];
            $user = $serverData[$serverID]['user'];
            $pass = $serverData[$serverID]['password'];
            $db = $serverData[$serverID]['db'];
            
            self::$serverDB = self::connect($host, $user, $pass, $db);
        }

        return ($conn == 'donate') ? self::$donateDB : self::$serverDB;
    }
    
    public static function query($sql, $params = array(), $conn = 'donate', $serverID = 0) {
        if ( ! self::dbh($conn, $serverID)) return false;
        
        $sth = self::dbh($conn, $serverID)->prepare($sql);
        $sth->execute($params);
        
        return $sth;
    }
    
    public static function lastInsertId($sql, $params = array(), $conn = 'donate', $serverID = 0) {
        if ( ! self::dbh($conn, $serverID)) return false;
        
        $sth = self::dbh($conn, $serverID)->prepare($sql);
        $sth->execute($params);
        
        $id = self::dbh($conn)->lastInsertId();
        
        return $id;
    }
    
    public static function first($sql, $params = array(), $conn = 'donate', $serverID = 0) {
        if ( ! self::dbh($conn, $serverID)) return false;
        
        $sth = self::dbh($conn, $serverID)->prepare($sql);
        $sth->execute($params);
        
        return $sth->fetch();
    }
    
    public static function get($sql, $params = array(), $conn = 'donate', $serverID = 0) {
        if ( ! self::dbh($conn, $serverID)) return false;
        
        $sth = self::dbh($conn, $serverID)->prepare($sql);
        $sth->execute($params);
        
        return $sth->fetchAll();
    }
}