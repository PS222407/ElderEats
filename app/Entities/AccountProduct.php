<?php

namespace App\Entities;

use Carbon\Carbon;

class AccountProduct
{
    public Carbon $expirationDate;
    public Carbon $ranOutAt;
    public Carbon $createdAt;
    public Carbon $updatedAt;

    public function __construct(
        public int $id,
        ?string $expirationDate,
        ?string $ranOutAt,
        string $createdAt,
        string $updatedAt,
    ) {
        $this->expirationDate = Carbon::create($expirationDate);
        $this->ranOutAt = Carbon::create($ranOutAt);
        $this->createdAt = Carbon::create($createdAt);
        $this->updatedAt = Carbon::create($updatedAt);
    }
}
