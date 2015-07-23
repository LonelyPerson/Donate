<?php

// save sessions to database
class SessionStorage {
    function __construct() {
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
        
        register_shutdown_function('session_write_close');
        
        session_start();
    }
    
    public function open() {}
    public function close() {}
    
    public function read($key) {
        $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));
        
        if (isset($results->data) && ! empty($results->data))
            return $results->data;
        
        return false;
    }
    
    public function readAndDestroy($key) {
        $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));
        
        $this->destroy($key);
        
        if (isset($results->data) && ! empty($results->data)) {
            $data = $results->data;
            return $data;
        }
        
        return false;
    }
    
    public function write($key, $value) {
        DB::query("REPLACE INTO sessions SET id = ?, access = ?, data = ?", array($key, time(), $value));
    }
    
    public function destroy($key) {
        DB::query('DELETE FROM sessions WHERE id = ?', array($key));
    }
    
    public function has($key) {
        $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));
        
        if (isset($results->data) && ! empty($results->data))
            return true;
        
        return false;
    }
    
    public function destroyAll() {
        DB::query('DELETE FROM sessions');
    }
    
    public function gc($max) {
        $old = time() - $max;

        DB::query('DELETE * FROM sessions WHERE access < ?', array($old));
    }
}