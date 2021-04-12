<?php

namespace App\Component\Cache\Manager;

use App\Tcp\Constant\CacheKeys;

class EventManager extends BaseCacheQueue
{
    public function getCacheKey(): string
    {
        return CacheKeys::EVENT_MANAGER_KEY;
    }

}