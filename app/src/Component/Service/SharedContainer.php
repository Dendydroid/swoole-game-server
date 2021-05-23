<?php

namespace App\Component\Service;

use App\Component\Container\Container;
use App\Tcp\Constant\CacheKeys;

class SharedContainer extends BaseSharedService
{
    public function getCacheKey(): string
    {
        return CacheKeys::SHARED_CONTAINER;
    }

    public function getContainer(): ?Container
    {
        return $this->shared->get();
    }

    public function setContainer($container): bool
    {
        return $this->shared->set($container);
    }
}