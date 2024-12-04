<?php

namespace Http\Core\Session;

class SessionManager
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
    }

    public function regenerateId()
    {
        session_regenerate_id(true);
    }
}