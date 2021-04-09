<?php

namespace App\Tcp\Helper;

use Symfony\Component\Uid\Uuid;

class Id
{
    public static function generateId(string $salt): string
    {
        $namespace = Uuid::v4();
        return Uuid::v5($namespace, $salt);
    }
}