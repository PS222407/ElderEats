<?php

namespace App\Entities;

use App\Enums\ConnectionStatus;
use Carbon\Carbon;

class AccountUser
{
    public Carbon $createdAt;
    public Carbon $updatedAt;

    public function __construct(
        public ConnectionStatus $status,
        public User $user,
        ?string $createdAt,
        ?string $updatedAt,
    ) {
        $this->createdAt = new Carbon($createdAt);
        $this->updatedAt = new Carbon($updatedAt);
    }
}
