<?php

namespace App\Classes;

use Illuminate\Support\Str;
use App\Models\Account as AccountModel;

class Account
{
    static ?AccountModel $accountModel = null;

    public static function generateTempToken(): string
    {
        if (self::$accountModel->temporary_token_expires_at <= now()) {
            self::$accountModel->update([
                'temporary_token' => Str::random(5),
                'temporary_token_expires_at' => now()->addMinutes(10),
            ]);
        }

        return self::$accountModel->temporary_token;
    }

    public static function refresh(): void
    {
        $accountModel = AccountModel::firstWhere('token', $_COOKIE['account_token'] ?? null);

        if (isset($_COOKIE['account_token']) && $accountModel) {
            setcookie('account_token', $accountModel->token, time() + (86400 * 400));
        } elseif ((isset($_COOKIE['account_token']) && !$accountModel || !isset($_COOKIE['account_token']))) {
            $token = Str::uuid();
            setcookie('account_token', $token, time() + (86400 * 400));
            $accountModel = AccountModel::create([
                'token' => $token,
            ]);
        }

        self::$accountModel = $accountModel;
    }
}
