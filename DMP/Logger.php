<?php

namespace DMP;

use DMP\Config;

class Logger
{
    private $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function log($ex)
    {
        echo 'Oops something went wrong! Watch in the logs!';

        $file_name = 'Log-' . date('d-m-Y') . '.txt';
        $target_dir = $this->config->getLogRoot();

        if (substr($target_dir, -1, 0) !== '/') {
            $target_dir .= '/';
        }

        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }

        $target_file = $target_dir . $file_name;

        file_put_contents($target_file, date("Y-m-d H:i:s") . " - " . $ex . "\n\n", FILE_APPEND);
    }
}