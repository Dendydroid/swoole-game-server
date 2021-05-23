<?php

namespace App\Tcp\Constant;

final class CacheKeys
{
    public const EVENT_MANAGER_KEY = "event_manager.list";
    public const COMMAND_MANAGER_KEY = "command.list";
    public const AUTH_TOKENS_KEY = "app.auth_tokens";
    public const CONNECTIONS_KEY = "app.connections";

    public const APPLICATION_DATA_KEY = "app.shared_application_data";
    public const SHARED_CONTAINER = "app.shared_container";
    public const SERVER_KEY = "app.server";

    public const ROOM_KEY_PREFIX = "room_";

    public static function roomKey(string $id): string
    {
        return self::ROOM_KEY_PREFIX . $id;
    }
}