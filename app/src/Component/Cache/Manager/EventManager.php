<?php

namespace App\Component\Cache\Manager;

use App\Component\Cache\Cache;
use App\Component\Concurrent\Event\BaseEvent;
use App\Tcp\Constant\CacheKeys;

class EventManager
{
    public static function getEvents()
    {
        return Cache::get(CacheKeys::EVENT_MANAGER_KEY);
    }

    public static function updateEvents($data): bool
    {
        return Cache::set(CacheKeys::EVENT_MANAGER_KEY, $data);
    }

    public static function queue(BaseEvent $event): bool
    {
        $events = self::getEvents();

        $events[] = serialize($event); # Add a serialized event

        return self::updateEvents($events); # Update cache
    }

    public static function dispose($eventItem): bool
    {
        $events = self::getEvents();

        foreach ($events as $key => $event) {
            if ($eventItem === $event) {
                unset($events[$key]);
            }
        }

        return self::updateEvents($events);
    }
}