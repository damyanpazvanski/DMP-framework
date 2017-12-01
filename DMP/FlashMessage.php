<?php

namespace DMP;

class FlashMessage
{
    public function set($key, $value)
    {
        $key = (string) $key;
        $_SESSION['app.data']['flash.message'][$key] = $value;
    }

    public function check($key)
    {
        $key = (string) $key;

        if (isset($_SESSION['app.data']['flash.message'][$key]) &&
            $_SESSION['app.data']['flash.message'][$key] !== '') {
            return true;
        }

        return false;
    }

    public function get($key)
    {
        $key = (string) $key;
        $value = '';

        if ($this->check($key)) {
            $value = $_SESSION['app.data']['flash.message'][$key];
            unset($_SESSION['app.data']['flash.message'][$key]);
        }

        return $value;
    }

    public function getAll()
    {
        if (!isset($_SESSION['app.data']['flash.message'])) {
            $_SESSION['app.data']['flash.message'] = [];
        }

        return $_SESSION['app.data']['flash.message'];
    }

    public function flushAll()
    {
        unset($_SESSION['app.data']['flash.message']);
    }
}