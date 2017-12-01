<?php

namespace DMP\DatabaseManager;

class MemcacheMapper
{
    /**
     * Config database connections
     */
    private static $connections;

    /**
     * @var \Memcache $memcache
     */
    private static $memcache;

    public function __construct($connections)
    {
        self::$connections = $connections;
        self::$memcache  = new \Memcache();

        if (!self::$memcache) {
            throw new \MemcachedException('It has a problem with the Memcache!');
        }
    }

    public static function getConnection($name)
    {
        $name = strtolower($name);
        $host = self::$connections[$name]['database.host'] . ':' . self::$connections[$name]['database.port'];
        self::$memcache->connect($host);

        return self::$memcache;
    }
}