<?php

namespace App\Component\Service;

use App\Component\Abstract\Singleton;

abstract class BaseService extends Singleton
{
    public static function instance(): BaseService
    {
        return static::getInstance();
    }
}