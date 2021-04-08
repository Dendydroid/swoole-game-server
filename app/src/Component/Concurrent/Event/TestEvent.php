<?php

namespace App\Component\Concurrent\Event;

class TestEvent extends BaseEvent
{
    public int $fd;

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