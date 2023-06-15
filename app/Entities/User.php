<?php

namespace App\Entities;

use Carbon\Carbon;

class User
{
    public Carbon $createdAt;
    public Carbon $updatedAt;
    public Carbon $emailVerifiedAt;

    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        ?string $emailVerifiedAt,
        public string $token,
        public ?string $rememberToken,
        ?string $createdAt,
        ?string $updatedAt,
    ) {
        $this->createdAt = new Carbon($createdAt);
        $this->updatedAt = new Carbon($updatedAt);
        $this->emailVerifiedAt = new Carbon($emailVerifiedAt);
    }
}
