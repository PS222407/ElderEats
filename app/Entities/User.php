<?php

namespace App\Entities;

use Carbon\Carbon;

class User
{
    public ?Carbon $createdAt;
    public ?Carbon $updatedAt;
    public ?Carbon $emailVerifiedAt;

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
        $this->createdAt = $createdAt ? Carbon::create($createdAt) : null;
        $this->updatedAt = $updatedAt ? Carbon::create($updatedAt) : null;
        $this->emailVerifiedAt = $emailVerifiedAt ? Carbon::create($emailVerifiedAt) : null;
    }
}
