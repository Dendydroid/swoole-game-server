<?php

namespace App\Component\Abstract;

use Exception;

abstract class Singleton
{
    protected static array $instances = [];

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception(__CLASS__ . " is a singleton!");
    }

    public static function getInstance(): static
    {
        $class = static::class;
        if (!isset(static::$instances[$class])) {
            static::$instances[$class] = new static();
        }

        return static::$instances[$class];
    }

    public static function getInstances(): array
    {
        return static::$instances;
    }
}
