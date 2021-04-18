<?php

namespace App\Component\Concurrent\Process;

use App\Component\Application\GameApplication;
use App\Tcp\Auth\AuthService;

class CacheCleanerProcess extends BaseProcess
{
    public function getMain(): callable
    {
        $server = GameApplication::app()->getServer();
        return function ($process) use ($server) {
            while (true) {
                sleep(1);
                $authService = new AuthService();
                $authService->flushOldTokens();

            }
        };
    }
}