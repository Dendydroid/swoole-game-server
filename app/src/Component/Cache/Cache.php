<?php

namespace App\Component\Cache;

use App\Component\Abstract\Singleton;
use Memcached;

class Cache extends Singleton
{
    protected Memcached $memcached;

    public function __construct()
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer('cache', 11211);
    }

    public function storage(): Memcached
    {
        return $this->memcached;
    }

    public static function get(string $key)
    {
        return static::getInstance()->storage()->get($key);
    }

    public static function set(string $key, $value): bool
    {
        return static::getInstance()->storage()->set($key, $value);
    }

    public static function delete(string $key): bool
    {
        return static::getInstance()->storage()->delete($key);
    }

    public static function keys(): bool|array
    {
        return static::getInstance()->storage()->getAllKeys();
    }
}