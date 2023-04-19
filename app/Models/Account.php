<?php

namespace App\Models;

use App\Enums\ConnectionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'temporary_token',
        'temporary_token_expires_at',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')->withTimestamps();
    }

    public function usersInProcess(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')->wherePivot('status', ConnectionStatus::IN_PROCESS)->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'account_products')->withPivot(['id', 'expiration_date', 'ran_out_at'])->withTimestamps();
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'account_products')->withPivot(['id', 'expiration_date', 'ran_out_at'])->withTimestamps()->wherePivot('ran_out_at', '>', now())->orWherePivotNull('ran_out_at');
    }

    public function shoppingList(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'fixed_products')->withTimestamps();
    }
}
