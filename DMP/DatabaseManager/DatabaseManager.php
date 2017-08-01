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
        $conn = mysqli_connect(
            $this->connection['database.host'],
            $this->connection['database.user'],
            $this->connection['database.pass'],
            $this->connection['database.name'],
            $this->connection['database.port']
        );

        if (!$conn) {
            throw new \mysqli_sql_exception(mysqli_connect_errno());
        }

        return $conn;
    }

    public static function getConnection($name)
    {
        $conn = mysqli_connect(
            static::$conn[$name]['database.host'],
            static::$conn[$name]['database.user'],
            static::$conn[$name]['database.pass'],
            static::$conn[$name]['database.name'],
            static::$conn[$name]['database.port']
        );

        if ($conn->connect_error) {
            throw new \mysqli_sql_exception(mysqli_connect_errno());
        }

        return $conn;
    }
}