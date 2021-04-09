<?php

namespace App\Component\Connection;

use Swoole\Http\Request;

abstract class BaseConnection
{
    protected int $created;

    /* Connection ID */
    protected int $fd;

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fd = $request->fd;
        $this->created = time();
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function setCreated(int $created): static
    {
        $this->created = $created;
        return $this;
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

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): static
    {
        $this->request = $request;
        return $this;
    }
}