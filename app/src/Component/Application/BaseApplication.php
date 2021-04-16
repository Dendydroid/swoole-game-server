<?php

namespace App\Component\Application;

use App\Component\Cache\MultiThreadModel;
use App\Component\Connection\BaseConnection;
use App\Component\Connection\ClientConnection;
use App\Component\Container\Container;
use App\Component\Exception\ExceptionFormatter;
use App\Component\Server\GameServer;
use JetBrains\PhpStorm\Pure;
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
    protected static array $connections = [];

    protected GameServer $server;

    public function __construct(Server $server)
    {
        $this->server = new GameServer($server);
        $this->servicesContainerBuilder = new ContainerBuilder();

        static::$connections = static::getConnections();
    }

    #[Pure] public function getServer(): Server
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

    public function loadServices(string $path): void
    {
        static::$container = new Container();
        try {
            $loader = new YamlFileLoader($this->servicesContainerBuilder, new FileLocator(PROJECT_PATH));
            $loader->load($path);
        } catch (Throwable $exception) {
            error_log(ExceptionFormatter::toLogString($exception));
        }
    }

    public static function pullConnections(): void
    {
        static::$connections = MultiThreadModel::getOrCreate("connections", function () {
            return [];
        });
    }

    public static function pushConnections(): void
    {
        $connections = static::$connections;

        static::pullConnections();

        MultiThreadModel::persist("connections", array_merge($connections, static::$connections));
    }

    public static function getConnection(int $fd)
    {
        static::pullConnections();
        return $connections[$fd] ?? null;
    }

    public static function getConnections(): array
    {
        static::pullConnections();
        return static::$connections;
    }

    public static function getContainerData(): array
    {
        return static::$container->all();
    }

    public static function connect(Request $request, bool $force = false): void
    {
        static::pullConnections();
        if (isset(static::$connections[$request->fd]) && !$force) {
            error_log("Connection $request->fd already exists!");
            return;
        }
        static::$connections[$request->fd] = new ClientConnection($request);
        static::pushConnections();
    }

    public static function disconnect(int $fd): void
    {
        static::pullConnections();
        unset(static::$connections[$fd]);
        static::pushConnections();
    }

    public function run(): void
    {
        $this->server->init();

        $this->server->run();
    }
}