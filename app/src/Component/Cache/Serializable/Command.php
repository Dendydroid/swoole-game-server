<?php

namespace App\Component\Cache\Serializable;

class Command
{
    public float $microTime;

    public string $input;

    public string $fd;

    public function __construct(string $input, int $fd)
    {
        $this->microTime = microtime(true);
        $this->input = $input;
        $this->fd = $fd;
    }
}