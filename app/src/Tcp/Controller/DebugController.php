<?php

namespace App\Tcp\Controller;

use App\Component\Cache\Cache;
use App\Tcp\Constant\Defaults;

class DebugController extends BaseController
{
    public function getDebug(array $data)
    {
        if(isset($data['key']) && $_ENV['DEBUG_PASSWORD'] === $data['key'])
        {
            Cache::set("DEBUG_CONNECTION", $this->frame->fd);
            $this->response(["debug" => Defaults::OK]);
        }
    }
}