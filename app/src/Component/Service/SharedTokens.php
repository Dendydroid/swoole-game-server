<?php

namespace App\Component\Service;

use App\Tcp\Constant\CacheKeys;

class SharedTokens extends BaseSharedService
{
    public function getCacheKey(): string
    {
        return CacheKeys::AUTH_TOKENS_KEY;
    }

    public function getTokens(): ?array
    {
        $tokens = $this->shared->get();

        return is_array($tokens) ? $tokens : [];
    }

    public function setTokens($tokens): bool
    {
        return $this->shared->set($tokens);
    }
}