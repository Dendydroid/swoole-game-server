<?php

namespace App\Cli;

use Codedungeon\PHPCliColors\Color;

class Reporter
{
    public static function reportMessage(string $message, string $color = COLOR::GREEN)
    {
        echo $color, $message, Color::RESET, PHP_EOL;
    }
}