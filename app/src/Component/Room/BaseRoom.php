<?php

namespace App\Component\Room;

use App\Component\Application\GameApplication;
use App\Component\Connection\ClientConnection;
use App\Component\Container\RoomContainer;
use App\Tcp\Helper\Id;
use App\Tcp\Packet\BasePacket;
use App\Tcp\Packet\LoadPacket;

abstract class BaseRoom
{
    protected string $id;
    protected array $connections;

    protected RoomContainer $container;

    public function __construct()
    {
        $this->id = Id::generateId(microtime());
        $this->container = new RoomContainer($this->id);
    }

    public function connect(ClientConnection $connection): bool
    {
        $this->connections[$connection->getFd()] = $connection;
        return true;
    }

    public function disconnect(ClientConnection $connection): bool
    {
        foreach ($this->connections as $fd => $activeConnection) {
            if ($fd === $activeConnection->getFd()) {
                unset($this->connections[$fd]);
                return true;
            }
        }
        return false;
    }

    public function broadcast(BasePacket $packet): void
    {
        foreach ($this->connections as $fd => $activeConnection) {
            $this->whisper($activeConnection, $packet);
        }
    }

    public function whisper(ClientConnection $connection, BasePacket $packet): void
    {
        GameApplication::app()->push($connection->getFd(), $packet);
    }

    public function load(ClientConnection $connection): void
    {
        $this->whisper($connection, new LoadPacket($this->container->all()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getContainer(): RoomContainer
    {
        return $this->container;
    }

    public function setContainer(RoomContainer $container): static
    {
        $this->container = $container;
        return $this;
    }
}