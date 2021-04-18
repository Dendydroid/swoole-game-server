<?php

namespace App\Component\Cache\MultiThread;

use App\Component\Cache\MultiThreadModel;
use App\Tcp\Constant\CacheKeys;

class Server extends MultiThreadModel
{
    public function getKey(): string
    {
        return CacheKeys::SERVER_KEY;
    }
}