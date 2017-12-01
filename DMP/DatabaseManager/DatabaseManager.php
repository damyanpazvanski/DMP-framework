<?php

namespace DMP\DatabaseManager;

class DatabaseManager
{
    private $connection;

    private static $conn;

    public function __construct($connection)
    {
        $this->connection = $connection;
        static::$conn = $connection;
    }

    public function makeConnection()
    {
        $conn = new \PDO('mysql:host=' . $this->connection['database.host'] . ';port=' . $this->connection['database.port'] . ';dbname=' . $this->connection['database.name'], $this->connection['database.user'], $this->connection['database.pass']);

        if (!$conn) {
            throw new \PDOException('The connection has a problem!');
        }

        return $conn;
    }

    public static function getConnection($name)
    {
        $conn = new \PDO('mysql:host=' . static::$conn[$name]['database.host'] . ';port=' . static::$conn[$name]['database.port'] . ';dbname=' . static::$conn[$name]['database.name'], static::$conn[$name]['database.user'], static::$conn[$name]['database.pass']);

        if (!$conn) {
            throw new \PDOException('The connection has a problem!');
        }

        return $conn;
    }
}