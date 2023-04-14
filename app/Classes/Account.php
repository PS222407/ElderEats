<?php

namespace App\Classes;

use Illuminate\Support\Str;

class Account
{
    static ?\App\Models\Account $account = null;

    public static function get(): ?\App\Models\Account
    {
        if (!self::$account) {
            self::$account = \App\Models\Account::firstWhere('token', $_COOKIE['account_token'] ?? null);
        }

        return self::$account;
    }

    public static function check(): bool
    {
        if (isset($_COOKIE['account_token']) && \App\Models\Account::firstWhere('token', $_COOKIE['account_token'])) {
            return true;
        }
        elseif (isset($_COOKIE['account_token']) && !\App\Models\Account::firstWhere('token', $_COOKIE['account_token'])) {
            setcookie('account_token', '', time() - 3600);
            $token = Str::uuid();
            self::register($token);
            self::login($token);

            return false;
        }

        return false;
    }

    public static function token(): ?string
    {
        return $_COOKIE['account_token'] ?? null;
    }

    public static function register(string $token): void
    {
        \App\Models\Account::create([
            'token' => $token,
        ]);
    }

    public static function login(string $token): bool
    {
        $account = \App\Models\Account::firstWhere('token', $token);

        if ($account) {
            setcookie('account_token', $token, time() + (86400 * 400));
        }

        self::$account = $account;

        return (bool)$account;
    }

    public static function extendCookie(): void
    {
        if (self::check()) {
            setcookie('account_token', self::token(), time() + (86400 * 400));
        }
    }

    public static function generateTempToken(): string
    {
        $account = self::get();

        if (self::$account->temporary_token_expires_at <= now()) {
            $account->update([
                'temporary_token' => Str::random(5),
                'temporary_token_expires_at' => now()->addMinutes(10),
            ]);
        }

        return $account->temporary_token;
    }
}
