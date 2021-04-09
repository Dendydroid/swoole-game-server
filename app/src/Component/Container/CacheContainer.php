<?php

namespace App\Component\Container;

use App\Component\Cache\Cache;

class CacheContainer extends Container
{
    public string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function save(): bool
    {
        return Cache::set($this->key, $this->data);
    }

    public function fetch(): void
    {
        $data = Cache::get($this->key);
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    public function dispose(): bool
    {
        return Cache::delete($this->key);
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
        $this->save();
    }

    public function get(string $key): mixed
    {
        $this->fetch();
        return $this->data[$key] ?? null;
    }

    public function all(): array
    {
        $this->fetch();
        return $this->data;
    }

    public function __destruct()
    {
        $this->dispose();
    }

}
