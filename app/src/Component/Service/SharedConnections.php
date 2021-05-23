<?php

namespace App\Component\Service;

use App\Component\Connection\ClientConnection;
use App\Database\Database;
use App\Database\Entity\User;
use App\Tcp\Constant\CacheKeys;

class SharedConnections extends BaseSharedService
{
    public function getCacheKey(): string
    {
        return CacheKeys::CONNECTIONS_KEY;
    }

    public function getConnections(): ?array
    {
        return $this->shared->get();
    }

    public function setConnections(array $connections): bool
    {
        return $this->shared->set($connections);
    }

    public function logoutConnectionByUserId(int $userId): ?ClientConnection
    {
        $database = new Database();
        $em = $database->getEntityManger();
        $connections = $this->getConnections();
        /** @var ClientConnection $connection */
        foreach ($connections as &$connection) {
            /** @var User|null $user */
            $user = $em->getRepository(User::class)->find($connection->getUserId());
            if ($user->getId() === $userId) {
                $connection->setUserId(0);
                $this->setConnections($connections);
                return $connection;
            }
        }
        return null;
    }

    public function loginConnectionByUserId(int $fd, int $userId): ?ClientConnection
    {
        $database = new Database();
        $em = $database->getEntityManger();
        $connections = $this->getConnections();
        /** @var ClientConnection $connection */
        foreach ($connections as &$connection) {
            if ($connection->getFd() === $fd) {
                $connection->setUserId($userId);
                $this->setConnections($connections);
                return $connection;
            }
        }
        return null;
    }
}