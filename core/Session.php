<?php

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegnerated = false;

    public function __construct()
    {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return $default;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function setAuthenticated($bool)
    {
        $this->set('_autheticated', (bool) $bool);

        $this->regnerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}
