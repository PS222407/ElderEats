<?php

namespace App\Entities;

use App\Classes\ApiEndpoint;
use App\Enums\ConnectionStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Account
{
    private string $baseUrl;

    public ?Carbon $temporaryTokenExpiresAt;
    public ?Carbon $notificationLastSentAt;
    public array $accountUsers = [];

    public function __construct(
        public int     $id,
        public ?string $name,
        public string  $token,
        public ?string $temporaryToken,
        ?string        $temporaryTokenExpiresAt,
        ?string        $notificationLastSentAt,
    ) {
        $this->baseUrl = config('app.api_base_url');

        $this->temporaryTokenExpiresAt = $temporaryTokenExpiresAt ? Carbon::create($temporaryTokenExpiresAt) : null;
        $this->notificationLastSentAt = $notificationLastSentAt ? Carbon::create($notificationLastSentAt) : null;
    }

    public function loadConnectedUsers()
    {
        $response = Http::withoutVerifying()->withHeaders([
            'x-api-key' => $this->token,
        ])->withUrlParameters([
            'id' => $this->id,
        ])->get($this->baseUrl . ApiEndpoint::GET_CONNECTED_USERS_FROM_ACCOUNT);

        if (!$response->ok()) {
            return null;
        }

        foreach ($response->json(['accountUsers']) as $accountUserArray) {
            $user = new User(
                name: $accountUserArray['user']['name'],
                email: $accountUserArray['user']['email'],
                emailVerifiedAt: $accountUserArray['user']['emailVerifiedAt'],
                token: $accountUserArray['user']['token'],
                rememberToken: $accountUserArray['user']['rememberToken'],
                createdAt: $accountUserArray['user']['createdAt'],
                updatedAt: $accountUserArray['user']['updatedAt'],
            );
            $accountUser = new AccountUser(
                status: ConnectionStatus::cases()[$accountUserArray['status']],
                user: $user,
                createdAt: $accountUserArray['createdAt'],
                updatedAt: $accountUserArray['updatedAt'],
            );

            $this->accountUsers[] = $accountUser;
        }

        return $this->accountUsers;
    }
}
