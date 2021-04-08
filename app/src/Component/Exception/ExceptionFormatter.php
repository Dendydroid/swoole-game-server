<?php

namespace App\Component\Exception;

use Throwable;

class ExceptionFormatter
{
    public static function toLogString(Throwable $exception, string $channel = "ERROR"): string
    {
        return " $channel: {$exception->getMessage()} ({$exception->getFile()}:{$exception->getLine()})";
    }

}