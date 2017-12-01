<?php

namespace DMP;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'DatabaseManager/PDOMapper.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '../config/config.php';

class Model
{
    public function __construct() {}

    protected function getManager($name)
    {
        $db_conn = \DMP\DatabaseManager\DatabaseManager::getConnection($name);
        $wrapper = new \DMP\DatabaseManager\PDOMapper($db_conn);

        return $wrapper;
    }

    protected function getCacheableMapper($name)
    {
        $mapper = '\DMP\DatabaseManager\\' . $name . 'Mapper';

        if (!class_exists($mapper)) {
            throw new \Exception('The Mapper is not found!');
        }

        $db_conn = $mapper::getConnection($name);

        return $db_conn;
    }
}