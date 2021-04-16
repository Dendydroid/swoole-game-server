<?php

namespace App\Tcp\Controller;

use App\Component\Cache\Cache;
use App\Tcp\Constant\Defaults;

class DebugController extends BaseController
{
    public function getDebug(array $data): void
    {
        $debugConnections = Cache::get("DEBUG_CONNECTIONS");
        if (!is_array($debugConnections)) {
            $debugConnections = [];
        }
        if (!in_array($this->frame->fd, $debugConnections, true)) {
            $debugConnections[] = $this->frame->fd;
            Cache::set("DEBUG_CONNECTIONS", $debugConnections);
            $this->response(["debug" => Defaults::OK]);
        }
    }
}