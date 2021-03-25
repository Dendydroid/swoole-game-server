<?php

namespace App\Tcp\Constant;

final class Defaults
{
    public const ROUTE_NOT_FOUND = [
        "status" => 404,
        "message" => "Route was not found"
    ];

    public const OK = [
        "status" => 200,
        "message" => "OK"
    ];

    public static function encode(string $item): string
    {
        return json_encode($item, JSON_PRETTY_PRINT);
    }
}