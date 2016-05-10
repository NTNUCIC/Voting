<?php
class SessionManager
{
    public static function start()
    {
        session_start();
    }

    public static function set($key, $value=null)
    {
        $_SESSION[$key]=is_null($value)?$key:$value;
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function clear()
    {
        session_unset();
    }
}
