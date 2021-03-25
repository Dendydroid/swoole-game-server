<?php

namespace App\Component\Application;

use App\Component\Connection\BaseConnection;
use App\Component\Connection\ClientConnection;
use App\Component\Container\Container;
use App\Component\Exception\ExceptionFormatter;
use App\Component\Server\BaseServer;
use App\Component\Server\GameServer;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Throwable;

abstract class BaseApplication
{
    protected static Container $container;

    protected ContainerBuilder $servicesContainerBuilder;

    /* @var array|BaseConnection[] $connections */
    protected static array $connections;

    protected GameServer $server;

    public function __construct(Server $server)
    {
        $this->server = new GameServer($server);
        $this->servicesContainerBuilder = new ContainerBuilder();
    }

    public function getServer(): Server
    {
        return $this->server->getServer();
    }

    public static function get(string $key): mixed
    {
        return static::$container->get($key);
    }

    public static function set(string $key, $value): void
    {
        static::$container->set($key, $value);
    }

    public static function has(string $key): bool
    {
        return static::$container->has($key);
    }

    public function loadServices(string $path)
    {
        static::$container = new Container();
        try {
            $loader = new YamlFileLoader($this->servicesContainerBuilder, new FileLocator(PROJECT_PATH));
            $loader->load($path);
        } catch (Throwable $exception) {
            error_log(ExceptionFormatter::toLogString($exception));
        }
    }

    public static function getConnection(int $fd)
    {
        return static::$connections[$fd];
    }

    public static function connect(Request $request, bool $force = false)
    {
        if (isset(static::$connections[$request->fd]) && !$force) {
            error_log("Connection $request->fd already exists!");
            return;
        }

        static::$connections[$request->fd] = new ClientConnection($request);
    }

    public static function disconnect(int $fd)
    {
        unset(static::$connections[$fd]);
    }

    public function run()
    {
        $this->server->init();

        $this->server->run();
    }
}