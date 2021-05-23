<?php

namespace App\Component\Concurrent\Process;

use App\Tcp\Auth\AuthService;

class CacheCleanerProcess extends BaseProcess
{
    public function getMain(): callable
    {
        return function ($process) {
            while (true) {
                sleep(1);
                $authService = new AuthService();
                $authService->flushOldTokens();
            }
        };
    }
}
