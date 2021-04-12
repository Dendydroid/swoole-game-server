<?php

namespace App\Tcp\Controller;

use App\Component\Cache\Cache;
use App\Tcp\Constant\Defaults;

class DebugController extends BaseController
{
    public function getDebug(array $data): void
    {
        Cache::set("DEBUG_CONNECTION", $this->frame->fd);
        $this->response(["debug" => Defaults::OK]);
    }
}