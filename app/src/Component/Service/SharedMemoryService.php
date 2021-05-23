<?php

namespace App\Component\Service;

use App\Component\Cache\Cache;

class SharedMemoryService extends BaseService
{
    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function get()
    {
        $data = Cache::get($this->key);
        return $data === false ? null : $data;
    }

    public function set($data): bool
    {
        return Cache::set($this->key, $data);
    }
}