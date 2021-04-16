<?php

namespace App\Component\Cache;

use Memcached;

class Cache
{
    protected static ?Memcached $memcached = null;

    public static function getInstance(): Memcached
    {
        if (static::$memcached === null) {
            static::$memcached = new Memcached();
            static::$memcached->addServer('cache', 11211);
        }

        return static::$memcached;
    }

    public static function get(string $key)
    {
        return static::getInstance()->get($key);
    }

    public static function set(string $key, $value): bool
    {
        return static::getInstance()->set($key, $value);
    }

    public static function delete(string $key): bool
    {
        return static::getInstance()->delete($key);
    }

    public static function keys(): bool|array
    {
        return static::getInstance()->getAllKeys();
    }
}