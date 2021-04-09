<?php

namespace App\Tcp\Constant;

final class CacheKeys
{
    public const EVENT_MANAGER_KEY = "event_manager.list";
    public const PROCESS_MANAGER_KEY = "process_manager.list";
    public const ROOM_KEY_PREFIX = "room_";

    public static function roomKey(string $id): string
    {
        return self::ROOM_KEY_PREFIX . $id;
    }
}