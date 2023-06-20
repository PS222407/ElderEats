<?php

namespace App\Entities;

use Carbon\Carbon;

class AccountProduct
{
    public ?Carbon $expirationDate;
    public ?Carbon $ranOutAt;
    public ?Carbon $createdAt;
    public ?Carbon $updatedAt;

    public function __construct(
        public int $id,
        ?string $expirationDate,
        ?string $ranOutAt,
        string $createdAt,
        string $updatedAt,
    ) {
        $this->expirationDate = $expirationDate ? Carbon::create($expirationDate) : null;
        $this->ranOutAt = $ranOutAt ? Carbon::create($ranOutAt) : null;
        $this->createdAt = $createdAt ? Carbon::create($createdAt) : null;
        $this->updatedAt = $updatedAt ? Carbon::create($updatedAt) : null;
    }
}
