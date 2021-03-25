<?php

namespace App\Tcp\Controller;

use App\Component\Application\GameApplication;
use App\Tcp\Helper\Json;
use Swoole\WebSocket\Frame;

abstract class BaseController
{
    public ?Frame $frame;

    public function setFrame(Frame $frame)
    {
        $this->frame = $frame;
    }

    public function response(array|string $data)
    {
        $responseData = is_array($data) ? Json::encode($data) : $data;
        GameApplication::app()->push($this->frame->fd, $responseData);
    }
}