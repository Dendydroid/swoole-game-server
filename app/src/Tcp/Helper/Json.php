<?php

namespace App\Tcp\Helper;

class Json
{
    public static function encode(array $item): string
    {
        return json_encode($item, JSON_UNESCAPED_UNICODE);
    }

    public static function array(string $json): array
    {
        return json_decode($json, true) ?? [];
    }
}