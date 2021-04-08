<?php

namespace App\Component\Container;

/* An interface for for containers */

interface IContainer
{
    public function set(string $key, $value): void;

    public function get(string $key): mixed;

    public function has(string $key): bool;
}