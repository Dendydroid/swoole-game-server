<?php

namespace App\Component\Service;

use App\Component\Server\GameServer;
use App\Tcp\Constant\CacheKeys;

class SharedServer extends BaseSharedService
{
    public function getCacheKey(): string
    {
        return CacheKeys::SERVER_KEY;
    }

    public function getServer(): ?GameServer
    {
        return $this->shared->get();
    }

    public function setServer($server): bool
    {
        return $this->shared->set($server);
    }
}