<?php

namespace App\Entities;

use App\Enums\ConnectionStatus;
use Carbon\Carbon;

class AccountUser
{
    public ?Carbon $createdAt;
    public ?Carbon $updatedAt;

    public function __construct(
        public ConnectionStatus $status,
        public User $user,
        ?string $createdAt,
        ?string $updatedAt,
    ) {
        $this->createdAt = $createdAt ? Carbon::create($createdAt) : null;
        $this->updatedAt = $updatedAt ? Carbon::create($updatedAt) : null;
    }
}
