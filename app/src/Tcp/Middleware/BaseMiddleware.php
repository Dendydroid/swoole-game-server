<?php

namespace App\Tcp\Middleware;

abstract class BaseMiddleware
{
    /* Handle (Validate) the request */
    abstract public function __invoke(array $data): bool;
}