<?php

namespace App\Component\Concurrent\Process;

use App\Component\Cache\Manager\ProcessManager;
use Swoole\Process;

abstract class BaseProcess extends Process
{
    public function __construct()
    {
        parent::__construct($this->getMain());

        ProcessManager::set($this);
    }

    public function getProcessName(): string
    {
        return static::class;
    }

    abstract public function getMain(): callable;

}