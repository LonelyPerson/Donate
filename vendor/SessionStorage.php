<?php

// save sessions to database
class SessionStorage {
    private $type;

    function __construct() {
        $this->type = Settings::get('app.session');

        if ($this->type == 'database') {
            session_set_save_handler(
                array($this, "open"),
                array($this, "close"),
                array($this, "read"),
                array($this, "write"),
                array($this, "destroy"),
                array($this, "gc")
            );
        }

        session_start();
    }

    public function open() {}
    public function close() {}

    public function read($key) {
        if ($this->type == 'database') {
            $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));

            if (isset($results->data) && ! empty($results->data))
                return $results->data;
        } else {
            if (isset($_SESSION[$key]))
                return $_SESSION[$key];
        }

        return false;
    }

    public function readAndDestroy($key) {
        if ($this->type == 'database') {
            $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));

            $this->destroy($key);

            if (isset($results->data) && ! empty($results->data)) {
                $data = $results->data;
                return $data;
            }
        } else {
            if (isset($_SESSION[$key])) {
                $session = $_SESSION[$key];
                unset($_SESSION[$key]);
                return $session;
            }
        }

        return false;
    }

    public function write($key, $value) {
        if ($this->type == 'database') {
            DB::query("REPLACE INTO sessions SET id = ?, access = ?, data = ?", array($key, time(), $value));
        } else {
            $_SESSION[$key] = $value;
        }
    }

    public function destroy($key) {
        if ($this->type == 'database') {
            DB::query('DELETE FROM sessions WHERE id = ?', array($key));
        } else {
            if (isset($_SESSION[$key]))
                unset($_SESSION[$key]);
        }
    }

    public function has($key) {
        if ($this->type == 'database') {
            $results = DB::first('SELECT data FROM sessions WHERE id = :id', array(':id' => $key));

            if (isset($results->data) && ! empty($results->data))
                return true;
        } else {
            if (isset($_SESSION[$key]))
                return true;
        }

        return false;
    }

    public function destroyAll() {
        if ($this->type == 'database') {
            DB::query('DELETE FROM sessions');
        } else {
            $_SESSION = [];
        }
    }

    public function gc($max) {
        $old = time() - $max;

        DB::query('DELETE * FROM sessions WHERE access < ?', array($old));
    }
}
