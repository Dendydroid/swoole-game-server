<?php

namespace App\Component\Container;

use App\Tcp\Constant\CacheKeys;

class RoomContainer extends CacheContainer
{
    public function __construct(string $roomId)
    {
        parent::__construct(CacheKeys::roomKey($roomId));
    }
}