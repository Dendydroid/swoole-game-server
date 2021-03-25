<?php

namespace App\Component\Exception;

use Throwable;

class ExceptionFormatter
{
    public static function toLogString(Throwable $exception, string $channel = "ERROR"): string
    {
        $dateTimeString = date("Y-m-d H:i:s");
        return "[$dateTimeString] $channel: {$exception->getMessage()} ({$exception->getFile()}:{$exception->getLine()})";
    }

}