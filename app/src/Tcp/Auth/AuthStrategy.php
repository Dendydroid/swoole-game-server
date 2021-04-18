<?php

namespace App\Tcp\Auth;

class AuthStrategy
{
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function validPassword(string $passwordInput, string $passwordHash): bool
    {
        return password_verify($passwordInput, $passwordHash);
    }
}