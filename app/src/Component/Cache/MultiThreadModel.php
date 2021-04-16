<?php

namespace App\Component\Cache;

class MultiThreadModel
{
    public static function get(string $key)
    {
        return Cache::get($key);
    }

    public static function getOrCreate(string $key, callable $init, bool $forceRecreate = false)
    {
        $item = Cache::get($key);

        if($forceRecreate || is_bool($item))
        {
            $item = $init();
        }

        return $item;
    }

    public static function persist(string $key, $data): bool
    {
        return Cache::set($key, $data);
    }
}