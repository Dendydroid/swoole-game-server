<?php

namespace App\Component\Cache;

use Memcached;

class Cache
{
    protected static ?Memcached $memcached = null;

    public static function getInstance(): Memcached
    {
        if (self::$memcached === null) {
            self::$memcached = new Memcached();
            self::$memcached->addServer('cache', 11211);
        }

        return self::$memcached;
    }

    public static function get(string $key)
    {
        return self::getInstance()->get($key);
    }

    public static function set(string $key, $value): bool
    {
        return self::getInstance()->set($key, $value);
    }

    public static function keys(): bool|array
    {
        return self::getInstance()->getAllKeys();
    }
}