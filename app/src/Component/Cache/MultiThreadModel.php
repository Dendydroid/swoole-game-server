<?php

namespace App\Component\Cache;

abstract class MultiThreadModel
{
    abstract public function getKey(): string;

    public function get($default = null)
    {
        $data = Cache::get($this->getKey());

        if (is_bool($data)) {
            return $default;
        }

        return $data;
    }

    public function set($value): bool
    {
        return Cache::set($this->getKey(), $value);
    }
}