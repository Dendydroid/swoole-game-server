<?php

namespace App\Component\Server;

use Swoole\WebSocket\Server;

abstract class BaseServer
{
    protected Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    abstract public function init(): void;

    public function getServer(): Server
    {
        return $this->server;
    }

}