<?php

namespace App\Component\Container;

/* A basic container */

class Container implements IContainer
{
    protected array $data = [];

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}