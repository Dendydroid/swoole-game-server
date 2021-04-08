<?php

namespace App\Component\Cache\Manager;

use App\Component\Application\GameApplication;
use App\Component\Concurrent\Process\BaseProcess;

class ProcessManager
{
    public static function set(BaseProcess $process): void
    {
        GameApplication::set($process->getProcessName(), $process);
    }
}