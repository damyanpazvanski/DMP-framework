<?php

namespace DMP;

class Router
{
    const ROUTER_PATH = __DIR__ . '/../config/router.php';

    private $router;
    private $params = [];

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

        for ($i = 2; $i < count($path); $i++) {
            if (isset($path[$i])) {
                $uri .= '/' . $path[$i];
            }
        }

        $uri = explode('?', $uri);
        $prefix = substr($uri[0], 0, 1);

        if ($prefix !== '/') {
            $uri[0] = '/' . $uri[0];
        }

        foreach ($this->router as $key => $route) {

            if ($key == 'otherwise') {
                continue;
            }

            if ($uri[0] == $route['path'] && strtolower($method) == strtolower($route['method'])) {
                return $route;
            }

            $this->getParamsFromPlaceholders($uri[0], $route['path']);
            if (!empty($this->params) && strtolower($method) == strtolower($route['method'])) {
                return $route;
            }
        }

        $otherwise = $this->router[$this->router['otherwise']['name']];
        if (!is_array($otherwise) || count($otherwise) < 1) {
            throw new \TypeError('Have problem with the router!');
        }

        header('HTTP/1.1 404 Not Found');

        return $otherwise;
    }

    private function getParamsFromPlaceholders($url, $path)
    {
        $startPosition = strspn($url ^ $path, "\0");

        if (isset($path[$startPosition]) && $path[$startPosition] == '{') {
            $getParam = substr($url, $startPosition);
            $da = explode('/', $getParam, 2);

            $this->params[rtrim(substr($path, $startPosition + 1), '}')] = $da[0];

            $position = strpos($path, '}');
            $getPath = substr($path, $position + 2);

            if (isset($da[1])) {
                $getPathPosition = strpos($getPath, "/");
                $daPosition = strpos($da[1], "/");

                if ($getPathPosition === $daPosition) {
                    $this->getParamsFromPlaceholders($da[1], $getPath);
                    return;
                } else {
                    $this->params = array();
                }
            }
        } else {
            $this->params = array();
        }
    }

    public function getParams()
    {
        return $this->params;
    }
}