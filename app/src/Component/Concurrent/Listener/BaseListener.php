<?php

namespace App\Component\Concurrent\Listener;

abstract class BaseListener
{
    abstract public function listeningTo(): array;
}