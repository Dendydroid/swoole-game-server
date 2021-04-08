<?php

namespace App\Component\Cache\Manager;

use App\Component\Cache\Cache;
use App\Component\Concurrent\Event\BaseEvent;
use App\Tcp\Constant\CacheKeys;

class EventManager
{
    public static function queue(BaseEvent $event): bool
    {
        $data = Cache::get(CacheKeys::EVENT_MANAGER_KEY); # Get existing data

        $data[] = serialize($event); # Add a serialized event

        return Cache::set(CacheKeys::EVENT_MANAGER_KEY, $data); # Update cache
    }

    public static function getEvents()
    {
        return Cache::get(CacheKeys::EVENT_MANAGER_KEY);
    }

    public static function dispose($eventItem): bool
    {
        $events = Cache::get(CacheKeys::EVENT_MANAGER_KEY);

        foreach ($events as $key => $event)
        {
            if($eventItem === $event)
            {
                unset($events[$key]);
            }
        }

        return Cache::set(CacheKeys::EVENT_MANAGER_KEY, $events);
    }
}