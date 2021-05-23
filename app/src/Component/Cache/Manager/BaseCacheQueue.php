<?php

namespace App\Component\Cache\Manager;

use App\Component\Abstract\Singleton;
use App\Component\Cache\Cache;

abstract class BaseCacheQueue extends Singleton
{
    abstract public function getCacheKey(): string;

    public function getList()
    {
        return Cache::get($this->getCacheKey());
    }

    public function flush(): bool
    {
        return Cache::set($this->getCacheKey(), []);
    }

    public function updateList($data): bool
    {
        return Cache::set($this->getCacheKey(), $data);
    }

    public function queue(object $item): bool
    {
        $list = $this->getList();

        $list[] = serialize($item);

        return $this->updateList($list);
    }

    public function dispose($deleteItem): bool
    {
        $list = $this->getList();

        if (is_bool($list)) {
            $list = [];
        }

        foreach ($list as $key => $item) {
            if ($deleteItem === $item) {
                unset($list[$key]);
            }
        }

        return $this->updateList($list);
    }
}
