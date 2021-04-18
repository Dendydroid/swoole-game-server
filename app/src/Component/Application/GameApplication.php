<?php

namespace App\Component\Application;

use App\Component\Config\Config;
use App\Database\Database;

/**
 * @method static GameApplication app()
 * @method static array processes()
 * @method static array listeners()
 * @method static array commands()
 * @method static Config config()
 * @method static Database database()
 */
class GameApplication extends BaseApplication
{
    public function push(int $fd, string $data)
    {
        if ($this->getServer()->confirm($fd)) {
            return $this->getServer()->push($fd, $data);
        }

        return null;
    }

    /* Get item from container */
    public static function __callStatic(string $name, array $arguments)
    {
        if (!method_exists(static::class, $name) && static::has($name)) {
            return static::get($name);
        }

        return null;
    }


}