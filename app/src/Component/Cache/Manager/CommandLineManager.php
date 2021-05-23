<?php

namespace App\Component\Cache\Manager;

use App\Tcp\Constant\CacheKeys;

class CommandLineManager extends BaseCacheQueue
{
    public function getCacheKey(): string
    {
        return CacheKeys::COMMAND_MANAGER_KEY;
    }
}
