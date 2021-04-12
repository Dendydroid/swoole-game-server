<?php

namespace App\Tcp\Middleware;

class DebugAuthenticated extends BaseMiddleware
{
    public function __invoke(array $data): bool
    {
        return isset($data['key']) && $_ENV['DEBUG_PASSWORD'] === $data['key'];
    }
}