<?php

namespace DMP;

class Router
{
    const ROUTER_PATH = __DIR__ . '/../config/router.php';

    private $router;

    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        $route = require self::ROUTER_PATH;
        $this->router = $route;
    }

    public function findAction($path, $method = 'GET')
    {
        $path = preg_split('/[\/]/', $path);
        $uri = $path[1];

        if (isset($path[2])) {
            $uri .= '/' . $path[2];
        }

        $uri = explode('?', $uri);
        $prefix = substr($uri[0], 0, 1);

        if ($prefix !== '/') {
            $uri[0] = '/' . $uri[0];
        }

        foreach ($this->router as $key => $route) {
            if ($key !== 'otherwise' && $uri[0] == $route['path'] && strtolower($method) == strtolower($route['method'])) {
                return $route;
            }
        }

        $otherwise = $this->router[$this->router['otherwise']['name']];
        if (!is_array($otherwise) || count($otherwise) < 1) {
            throw new \TypeError('Have problem with the router!');
        }

        return $otherwise;
    }
}