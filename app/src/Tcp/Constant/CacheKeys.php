<?php

namespace App\Tcp\Constant;

final class CacheKeys
{
    public const SWOOLE_SERVER_KEY = "server.instance";
    public const EVENT_MANAGER_KEY = "event_manager.list";
    public const COMMAND_MANAGER_KEY = "command.list";
    public const PROCESS_MANAGER_KEY = "process_manager.list";
    public const ROOM_KEY_PREFIX = "room_";

    public static function roomKey(string $id): string
    {
        return self::ROOM_KEY_PREFIX . $id;
    }
}