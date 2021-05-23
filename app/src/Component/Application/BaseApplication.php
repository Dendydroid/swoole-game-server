<?php

namespace App\Component\Application;

use App\Component\Connection\ClientConnection;
use App\Component\Container\Container;
use App\Component\Exception\ExceptionFormatter;
use App\Component\Server\GameServer;
use App\Component\Service\SharedApplicationData;
use App\Component\Service\SharedConnections;
use App\Component\Service\SharedContainer;
use App\Database\Entity\User;
use JetBrains\PhpStorm\Pure;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Throwable;

abstract class BaseApplication
{
    protected Container $container;

    protected SharedConnections $connections;

    protected SharedApplicationData $appData;

    protected SharedContainer $sharedContainer;

    protected ContainerBuilder $servicesContainerBuilder;

    protected GameServer $server;

    public function __construct(Server $server)
    {
        $this->server = new GameServer($server);
        $this->servicesContainerBuilder = new ContainerBuilder();
        $this->connections = new SharedConnections();
        $this->appData = new SharedApplicationData();
        $this->sharedContainer = new SharedContainer();
        $this->connections->setConnections([]);
    }

    #[Pure] public function getServer(): Server
    {
        return $this->server->getServer();
    }

    public function appData(): SharedApplicationData
    {
        return $this->appData;
    }

    public function get(string $key): mixed
    {
        return $this->container->get($key);
    }

    public function set(string $key, $value, bool $share = true): void
    {
        $this->container->set($key, $value);
        if ($share) {
            $this->sharedContainer->setContainer($this->container);
        }
    }

    public function has(string $key): bool
    {
        return $this->container->has($key);
    }

    public function loadServices(string $path): void
    {
        $this->container = new Container();
        try {
            $loader = new YamlFileLoader($this->servicesContainerBuilder, new FileLocator(PROJECT_PATH));
            $loader->load($path);
        } catch (Throwable $exception) {
            error_log(ExceptionFormatter::toLogString($exception));
        }
    }

    public function getSharedConnections(): SharedConnections
    {
        return $this->connections;
    }

    public function getConnection(int $fd): ?ClientConnection
    {
        /** @var ClientConnection $connection */
        foreach ($this->getConnections() as $connection) {
            if ($connection->getFd() === $fd) {
                return $connection;
            }
        }
        return null;
    }

    public function getConnectionByEmail(string $email): ?ClientConnection
    {
        $em = GameApplication::database()->getEntityManger();
        /** @var ClientConnection $connection */
        foreach ($this->getConnections() as $connection) {
            /** @var User|null $user */
            $user = $em->getRepository(User::class)->find($connection->getUserId());
            if ($user->getEmail() === $email) {
                return $connection;
            }
        }
        return null;
    }

    public function getConnectionByUserId(int $userId): ?ClientConnection
    {
        $em = GameApplication::database()->getEntityManger();
        /** @var ClientConnection $connection */
        foreach ($this->getConnections() as $connection) {
            /** @var User|null $user */
            $user = $em->getRepository(User::class)->find($connection->getUserId());
            if ($user->getId() === $userId) {
                return $connection;
            }
        }
        return null;
    }

    public function getConnections(): ?array
    {
        return $this->connections->getConnections();
    }

    public function getContainerData(): array
    {
        return $this->container->all();
    }

    public function connect(Request $request, bool $force = false): void
    {
        $connections = $this->getConnections() ?? [];
        if (isset($connections[$request->fd]) && !$force) {
            error_log("Connection $request->fd already exists!");
            return;
        }

        $connections[$request->fd] = new ClientConnection($request);
        $this->connections->setConnections($connections);

        dump("connections", $connections, $this->getConnections());
    }

    public function disconnect(int $fd): void
    {
        $connections = $this->getConnections() ?? [];
        unset($connections[$fd]);
        $this->connections->setConnections($connections);
    }

    public function run(): void
    {
        $this->server->init();

        $this->server->run();
    }
}
