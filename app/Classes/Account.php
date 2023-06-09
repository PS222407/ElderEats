<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Entities\Account as AccountEntity;

class Account
{
    public static ?AccountEntity $accountEntity = null;

    public static function generateTempToken(): string
    {
        if (self::$accountEntity->temporaryTokenExpiresAt <= now()) {
            $tempToken = random_int(100_000, 999_999);
            \App\Models\Account::find(self::$accountEntity->id)->update([
                'temporary_token' => $tempToken,
                'temporary_token_expires_at' => now()->addMinutes(10),
            ]);

            self::$accountEntity->temporaryToken = $tempToken;
        }

        return self::$accountEntity->temporaryToken;
    }

    public static function refresh(): void
    {
        //$accountModel = AccountModel::firstWhere('token', $_COOKIE['account_token'] ?? null);
        $response = Http::withoutVerifying()->withUrlParameters([
            'token' => $_COOKIE['account_token'] ?? null,
        ])->get(config('app.api_base_url') . ApiEndpoint::GET_ACCOUNT_BY_TOKEN);

        $accountArray = $response->json();
        if ($response->ok()) {
            $accountEntity = new AccountEntity(
                id: $accountArray['id'],
                name: $accountArray['name'],
                token: $accountArray['token'],
                temporaryToken: $accountArray['temporaryToken'],
                temporaryTokenExpiresAt: $accountArray['temporaryTokenExpiresAt'],
                notificationLastSentAt: $accountArray['notificationLastSentAt'],
            );
        } else {
            $accountEntity = null;
        }

        if (isset($_COOKIE['account_token']) && $accountEntity) {
            setcookie('account_token', $accountEntity->token, time() + (86400 * 400), '/');
        } elseif (((isset($_COOKIE['account_token']) && !$accountEntity) || !isset($_COOKIE['account_token']))) {
            $token = Str::uuid();
            setcookie('account_token', $token, time() + (86400 * 400), '/');
            $response2 = Http::withoutVerifying()->post(config('app.api_base_url') . ApiEndpoint::STORE_ACCOUNT, [
                'token' => $token,
            ]);

            $accArr = $response2->json();

            $accountEntity = new AccountEntity(
                id: $accArr['id'],
                name: $accArr['name'],
                token: $accArr['token'],
                temporaryToken: $accArr['temporaryToken'],
                temporaryTokenExpiresAt: $accArr['temporaryTokenExpiresAt'],
                notificationLastSentAt: $accArr['notificationLastSentAt'],
            );
        }

        self::$accountEntity = $accountEntity;
    }
}
