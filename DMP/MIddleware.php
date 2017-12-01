<?php

namespace DMP;

class MIddleware
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function middleware($name)
    {
        $class = $name . 'Middleware.php';

        if (!file_exists($this->path . $class)) {
            throw new \Exception('The file: ' . $this->path . $name . 'Middleware.php does not exist!');
        }

        include_once($this->path . $class);

        $class = substr($class, 0, -4);
        $middleware = new $class();

        if (!method_exists($name . 'Middleware','call')) {
            throw new \Exception('The Middleware has to include "call" method!');
        }

        $middleware->call();
    }
}