<?php

namespace App\Component\Service;

use App\Tcp\Constant\CacheKeys;
use stdClass;

class SharedApplicationData extends BaseSharedService
{
    public function getCacheKey(): string
    {
        return CacheKeys::APPLICATION_DATA_KEY;
    }

    public function getData(): ?object
    {
        return (object)$this->shared->get();
    }

    public function setKey(string $key, $value): bool
    {
        $appData = $this->getData();
        if ($appData === null) {
            $appData = new stdClass();
        }
        $appData->{$key} = $value;
        return $this->shared->set($appData);
    }
}