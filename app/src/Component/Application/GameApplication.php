<?php

namespace App\Component\Application;

use App\Component\Config\Config;
use App\Database\Database;

/**
 * @method static array processes()
 * @method static array listeners()
 * @method static array commands()
 * @method static Config config()
 * @method static Database database()
 */
class GameApplication extends BaseApplication
{
    public static ?GameApplication $app = null;

    public function push(int $fd, string $data)
    {
        if ($this->getServer()->confirm($fd)) {
            return $this->getServer()->push($fd, $data);
        }

        return null;
    }

    public static function app(): ?GameApplication
    {
        return static::$app;
    }

    /* Get item from container */
    public static function __callStatic(string $name, array $arguments)
    {
        if ((static::$app !== null) && !method_exists(static::class, $name) && static::$app->has($name)) {
            return static::$app->get($name);
        }
        return null;
    }
}
