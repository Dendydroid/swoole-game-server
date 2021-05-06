<?php

namespace App\Tcp\Auth;

use App\Component\Application\GameApplication;
use App\Component\Cache\Cache;
use App\Component\Cache\MultiThread\Tokens;
use App\Component\Cache\Serializable\Token;
use App\Database\Entity\User;
use App\Database\Traits\DatabaseAccess;
use App\Tcp\Constant\CacheKeys;
use App\Tcp\Helper\Id;
use JetBrains\PhpStorm\Pure;

class AuthService
{
    use DatabaseAccess;

    protected Tokens $tokenStorage;

    #[Pure] public function __construct()
    {
        $this->tokenStorage = new Tokens();
    }

    /* Generate token for user and add store it */
    public function generateToken(int $userId): string
    {
        $ttl = GameApplication::config()->get("cache")["ttl"] ?? 5;
        $tokens = $this->tokenStorage->get([]);
        $token = new Token();
        $token->userId = $userId;
        $token->token = Id::generateId($userId . microtime());
        $token->maxAge = $ttl;
        $tokens[] = $token;
        $this->tokenStorage->set($tokens);
        return $token->token;
    }

    public function findToken(string $token): ?Token
    {
        $tokens = $this->tokenStorage->get([]);
        /** @var Token $storedToken */
        foreach ($tokens as $storedToken) {
            if ($token === $storedToken->token) {
                return $storedToken;
            }
        }
        return null;
    }

    public function getUserId(string $token): int
    {
        $token = $this->findToken($token);
        return $token->userId ?? 0;
    }

    public function getUser(string $token): ?User
    {
        $token = $this->findToken($token);
        $user = null;
        if ($token) {
            /** @var User|null $user */
            $user = $this->em->getRepository(User::class)->find($token->userId);
        }
        return $user;
    }

    public function flushOldTokens(): void
    {
        $connections = Cache::get(CacheKeys::CONNECTIONS_KEY);

        if (is_bool($connections)) {
            $connections = [];
        }

        GameApplication::updateConnections($connections);

        $tokens = $this->tokenStorage->get([]);
        foreach ($tokens as $key => $storedToken) {
            if (($storedToken->ts + $storedToken->maxAge) < time()) {
                $tokenConnection = GameApplication::getConnectionByUserId($storedToken->userId);
                if ($tokenConnection) {
                    $tokenConnection->setUserId(0);
                    GameApplication::updateConnections();
                }
                unset($tokens[$key]);
            }
        }
        $this->tokenStorage->set($tokens);
    }
}