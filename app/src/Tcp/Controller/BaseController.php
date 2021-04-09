<?php

namespace App\Tcp\Controller;

use App\Component\Application\GameApplication;
use App\Component\Connection\ClientConnection;
use App\Tcp\Helper\Json;
use App\Tcp\Packet\BasePacket;
use Swoole\WebSocket\Frame;

abstract class BaseController
{
    public ?Frame $frame;
    public ?ClientConnection $connection;

    public function setFrame(Frame $frame): void
    {
        $this->frame = $frame;
        $this->setConnection($frame->fd);
    }

    public function setConnection(int $fd): void
    {
        $this->connection = GameApplication::getConnection($fd);
    }

    public function response(array|string $data): void
    {
        $responseData = is_array($data) ? Json::encode($data) : $data;
        GameApplication::app()->push($this->frame->fd, $responseData);
    }
}