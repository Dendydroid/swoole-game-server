<?php

namespace App\Component\Connection;

use Swoole\Http\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

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

    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     * @return BaseConnection
     */
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