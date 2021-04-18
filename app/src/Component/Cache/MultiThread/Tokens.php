<?php

namespace App\Component\Cache\MultiThread;

use App\Component\Cache\MultiThreadModel;
use App\Tcp\Constant\CacheKeys;

class Tokens extends MultiThreadModel
{
    public function getKey(): string
    {
        return CacheKeys::AUTH_TOKENS_KEY;
    }
}