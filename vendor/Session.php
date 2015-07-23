<?php

class Session {
    public static function handler() {
        return new SessionStorage();
    }
    
    public static function get($key) {
        return self::handler()->read($key);
    }
    
    public static function pull($key) {
        return self::handler()->readAndDestroy($key);
    }
    
    public static function put($key, $value) {
        return self::handler()->write($key, $value);
    }
    
    public static function forget($key) {
        return self::handler()->destroy($key);
    }
    
    public static function has($key) {
        return self::handler()->has($key);
    }
    
    public static function destroy() {
        return self::handler()->destroyAll();
    }
}