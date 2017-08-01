<?php

namespace DMP;

class Config
{
    const CONFIG_PATH = __DIR__ . '/../config/config.php';

    private $config;

    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        $config = require self::CONFIG_PATH;

        if (!is_array($config)) {
            throw new \TypeError('Problem with the config file!');
        }

        $this->config = $config;
    }

    public function getImageRoot()
    {
        return $this->config['images.path'];
    }

    public function getLogRoot()
    {
        return $this->config['log.path'];
    }

    public function getTemplateRoot()
    {
        return $this->config['templates.path'];
    }

    public function getConnections()
    {
        return $this->config['database'];
    }


}