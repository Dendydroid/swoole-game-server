<?php

namespace App\Tcp\Auth;

use App\Component\Cache\Serializable\Token;
use App\Component\Config\Config;
use App\Component\Service\SharedConnections;
use App\Component\Service\SharedTokens;
use App\Database\Entity\User;
use App\Database\Traits\DatabaseAccess;
use App\Tcp\Helper\Id;

class AuthService
{
    use DatabaseAccess;

    protected SharedTokens $tokenStorage;
    protected SharedConnections $sharedConnections;
    protected Config $config;

    public function __construct()
    {
        $this->tokenStorage = new SharedTokens();
        $this->sharedConnections = new SharedConnections();
        $this->config = Config::getInstance()->setConfigFolder(CONFIG_DATA_PATH)->load();
    }

    /* Generate token for user and add store it */
    public function generateToken(int $userId): string
    {
        $ttl = $this->config->get("cache")["ttl"] ?? 5;
        $tokens = $this->tokenStorage->getTokens();
        $token = new Token();
        $token->userId = $userId;
        $token->token = Id::generateId($userId . microtime());
        $token->maxAge = $ttl;
        $tokens[] = $token;
        $this->tokenStorage->setTokens($tokens);
        return $token->token;
    }

    public function findToken(string $token): ?Token
    {
        $tokens = $this->tokenStorage->getTokens();
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
        $tokens = $this->tokenStorage->getTokens();
        foreach ($tokens as $key => $storedToken) {
            if (($storedToken->ts + $storedToken->maxAge) < time()) {
                $tokenConnection = $this->sharedConnections->logoutConnectionByUserId($storedToken->userId);
                unset($tokens[$key]);
            }
        }
        $this->tokenStorage->setTokens($tokens);
    }
}