<?php

namespace App\Component\Cache\Serializable;

class Token
{
    public int $ts;

    public int $maxAge;

    public string $token;

    public int $userId;

    public function __construct()
    {
        $this->ts = time();
    }

}