<?php

namespace DMP;

class Templates
{
    private $path;
    private $root;
    private $params;

    public function __construct($path, $root, $params = [])
    {
        $this->path = $path;
        $this->root = $root;
        $this->params = $params;
    }

    public function render()
    {
        $file_path = $this->root . $this->path . '.php';
        if (!file_exists($file_path)) {
            throw new \Exception('This template doesn\'t exist!');
        }

        $params = $this->params;
        include_once $file_path;
    }
}