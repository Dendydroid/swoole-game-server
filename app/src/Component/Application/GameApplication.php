<?php

namespace App\Component\Application;

/**
 * @method static GameApplication app()
 * @method static GameApplication processes()
 * @method static GameApplication listeners()
 * @method static GameApplication commands()
 */
class GameApplication extends BaseApplication
{
    public function push(int $fd, string $data)
    {
        return $this->getServer()->push($fd, $data);
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