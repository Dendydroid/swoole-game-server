<?php

namespace App\Component\Application;

use App\Component\Cache\Cache;
use App\Component\Connection\ClientConnection;
use App\Component\Container\Container;
use App\Component\Exception\ExceptionFormatter;
use App\Component\Server\GameServer;
use App\Database\Entity\User;
use App\Tcp\Constant\CacheKeys;
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

    protected GameServer $server;

    public function __construct(Server $server)
    {
        $this->server = new GameServer($server);
        $this->servicesContainerBuilder = new ContainerBuilder();
    }

    #[Pure] public function getServer(): Server
    {
        return $this->server->getServer();
    }

    public static function get(string $key): mixed
    {
        return static::$container->get($key);
    }

    public static function set(string $key, $value, bool $cache = false): void
    {
        static::$container->set($key, $value);

        if ($cache) {
            Cache::set($key, $value);
        }
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

    public static function getConnection(int $fd): ?ClientConnection
    {
        /** @var ClientConnection $connection */
        foreach (static::getConnections() as $connection) {
            if ($connection->getFd() === $fd) {
                return $connection;
            }
        }
        return null;
    }

    public static function getConnectionByEmail(string $email): ?ClientConnection
    {
        $em = GameApplication::database()->getEntityManger();
        /** @var ClientConnection $connection */
        foreach (static::getConnections() as $connection) {
            /** @var User|null $user */
            $user = $em->getRepository(User::class)->find($connection->getUserId());
            if ($user->getEmail() === $email) {
                return $connection;
            }
        }
        return null;
    }

    public static function getConnectionByUserId(int $userId): ?ClientConnection
    {
        $em = GameApplication::database()->getEntityManger();
        /** @var ClientConnection $connection */
        foreach (static::getConnections() as $connection) {
            /** @var User|null $user */
            $user = $em->getRepository(User::class)->find($connection->getUserId());
            if ($user->getId() === $userId) {
                return $connection;
            }
        }
        return null;
    }

    public static function getConnections(): array
    {
        return static::get(CacheKeys::CONNECTIONS_KEY) ?? [];
    }

    public static function getContainerData(): array
    {
        return static::$container->all();
    }

    public static function connect(Request $request, bool $force = false): void
    {
        $connections = static::getConnections();

        if (isset($connections[$request->fd]) && !$force) {
            error_log("Connection $request->fd already exists!");
            return;
        }

        $connections[$request->fd] = new ClientConnection($request);

        static::updateConnections($connections);

    }

    public static function updateConnections(array $connections = null): void
    {
        if($connections !== null)
        {
            static::set(CacheKeys::CONNECTIONS_KEY, $connections, true);
            return;
        }

        static::set(CacheKeys::CONNECTIONS_KEY, static::getConnections(), true);
    }

    public static function disconnect(int $fd): void
    {
        $connections = static::getConnections();
        unset($connections[$fd]);
        static::updateConnections($connections);
    }

    public function run(): void
    {
        $this->server->init();

        $this->server->run();
    }
}