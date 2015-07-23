<?php

class SQL {
    public static function get($key, $serverID = 1) {
        $key = explode('.', $key);

        $sql = include CONFIG_PATH . '/sql.php';

        if (Session::has('active_server_id'))
            $serverID = Session::get('active_server_id');

        $result = $sql[$serverID][$key[1]][$key[2]];

        return $result;
    }
}
