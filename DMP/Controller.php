<?php

namespace DMP;

use DMP\Router;
use DMP\Config;

class Controller
{
    private $router;
    private $config;

    public function __construct()
    {
        $this->router = new Router();
        $this->config = new Config();
    }

    public function redirect($uri)
    {
        $uriAndQuery = explode('?', $uri);
        $uri = $uriAndQuery[0];
        $query = $uriAndQuery[1];
        $action = $this->router->findAction($uri);

        if ($action['path'] !== $uri) {
            throw new \HttpUrlException('This path doesn\'t exist!');
        }

        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

        header("Location: http://$host$uri" . $action['path'] . '?' . $query, true);
    }

    public function getManager($name)
    {
        $db_conn = \DMP\DatabaseManager\DatabaseManager::getConnection($name);
        return$db_conn;
    }

    public function getImagesFolderPath()
    {
        return $this->config->getImageRoot();
    }

    public function getUriParams()
    {
        $path = $_SERVER['REQUEST_URI'];
        $all_patams = explode('/', $path);

        return array_slice($all_patams, 2);
    }
}