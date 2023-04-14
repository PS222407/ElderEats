<?php

namespace App\Classes;

class Account
{
    public static function check(): bool
    {
        return isset($_COOKIE['account_token']);
    }

    public static function token(): ?string
    {
        return $_COOKIE['account_token'] ?? null;
    }

    public static function login(string $token): bool
    {
        $account = \App\Models\Account::firstWhere('token', $token);

        if ($account) {
            setcookie('account_token', $token, time() + (86400 * 400));
        }

        return (bool)$account;
    }

    public static function extendCookie(): void
    {
        if (self::check()) {
            setcookie('account_token', self::token(), time() + (86400 * 400));
        }
    }
}
