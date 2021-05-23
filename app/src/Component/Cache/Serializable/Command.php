<?php

namespace App\Component\Cache\Serializable;

use JetBrains\PhpStorm\Pure;

class Command
{
    public float $microTime;

    public string $input;

    public string $fd;

    #[Pure] public function __construct(string $input, int $fd)
    {
        $this->microTime = microtime(true);
        $this->input = $input;
        $this->fd = $fd;
    }
}
