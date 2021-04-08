<?php

namespace App\Tcp\Controller;

use App\Component\Cache\Manager\EventManager;
use App\Component\Concurrent\Event\TestEvent;
use App\Tcp\Constant\Defaults;

class MainController extends BaseController
{

    public function main(array $data)
    {
        $test = (new TestEvent())->setFd($this->frame->fd);
        EventManager::queue($test);

        $this->response(["request" => $data, "response" => Defaults::OK]);
    }
}