<?php

namespace App\Component\Service;

abstract class BaseSharedService
{
    protected SharedMemoryService $shared;

    public function __construct()
    {
        $this->shared = new SharedMemoryService($this->getCacheKey());
    }

    abstract public function getCacheKey(): string;
}