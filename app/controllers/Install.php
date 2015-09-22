<?php

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\DB;
use \Donate\Vendor\Output;
use \Donate\Vendor\File;
use \Donate\Vendor\SQL;

class Install {
    public function post_checkDBConnection() {
        if ( ! DB::isActive())
            return  Output::json(['status' => 'error']);

        return  Output::json(['status' => 'success']);
    }

    public function post_checkChmod() {
        if ( ! is_writable(APP_PATH . '/storage')) {
            return Output::json(['status' => 'error']);
        }

        return Output::json(['status' => 'success']);
    }

    public function post_startInstall() {
        ini_set('memory_limit', -1);
        set_time_limit(0);

        $query = File::read(ROOT_PATH . '/install/donate.sql');
        $query = SQL::removeRemarks($query);
        $query = SQL::splitFile($query, ';');

        foreach($query as $sql){
            DB::query($sql);
        }

        File::create(STORAGE_PATH . '/installed');

        return Output::json(['status' => 'success']);
    }
}
