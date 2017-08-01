<?php

namespace DMP;

class Request
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get($name, $default = null)
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) !== 'get') {
            throw new \HttpRequestMethodException('The method must be GET!');
        }

        $param = '';
        if (isset($this->request[$name])) {
            $param = $this->request[$name];
        }

        if ($param) {
            return $param;
        }

        return $default;
    }

    public function post($name)
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
            throw new \HttpRequestMethodException('The method must be POST!');
        }

        if (isset($_FILES) && isset($_FILES[$name])) {
            $this->request[$name] = $_FILES[$name];
        }

        if (!isset($this->request[$name])) {
            throw new \HttpInvalidParamException('Doesn\'t exist param with this name!');
        }

        $param = $this->request[$name];

        return $param;
    }
}