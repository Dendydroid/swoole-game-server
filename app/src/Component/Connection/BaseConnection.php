<?php

namespace App\Component\Connection;

use Swoole\Http\Request;

abstract class BaseConnection
{
    protected int $created;

    /* Connection ID */
    protected int $fd;

    public function __construct(Request $request)
    {
        $this->fd = $request->fd;
        $this->created = time();
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function getFd(): int
    {
        return $this->fd;
    }

    public function setFd(int $fd): static
    {
        $this->fd = $fd;
        return $this;
    }
}