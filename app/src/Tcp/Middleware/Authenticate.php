<?php

namespace App\Tcp\Middleware;

use App\Component\Cache\Serializable\Token;
use App\Tcp\Auth\AuthService;
use App\Tcp\Constant\Defaults;
use JetBrains\PhpStorm\Pure;

class Authenticate extends BaseMiddleware
{
    protected AuthService $auth;

    #[Pure] public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function __invoke(array $request): array
    {
        $token = $request["token"] ?? null;

        if (!$token) {
            return Defaults::UNAUTHORIZED;
        }

        $token = $this->auth->findToken($token);

        if ($token instanceof Token) {
            return [];
        }

        return Defaults::UNAUTHORIZED;
    }
}