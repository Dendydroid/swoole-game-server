<?php

namespace App\Component\Concurrent\Process;

use Swoole\Process;

abstract class BaseProcess extends Process
{
    public function __construct()
    {
        parent::__construct($this->getMain());
    }

    public function getProcessName(): string
    {
        return static::class;
    }

    abstract public function getMain(): callable;

}