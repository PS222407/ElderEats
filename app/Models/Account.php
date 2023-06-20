<?php

namespace App\Models;

use App\Enums\ConnectionStatus;
use Illuminate\Database\Eloquent\Builder;
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
        'notification_last_sent_at',
        'name',
    ];

    protected $casts = [
        'notification_last_sent_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')->withTimestamps();
    }

    public function usersInProcess(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')->wherePivot('status', ConnectionStatus::IN_PROCESS)->withTimestamps();
    }

    public function usersConnected(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')->wherePivot('status', ConnectionStatus::CONNECTED)->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'account_products')->withPivot(['id', 'expiration_date', 'ran_out_at'])->withTimestamps();
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'account_products')
            ->withPivot(['id', 'expiration_date', 'ran_out_at'])
            ->withTimestamps()
            ->where(function (Builder $query) {
                $query->whereRaw('ran_out_at > ?', [now()])
                    ->orWhereRaw('ran_out_at IS NULL');
            });
    }

    public function shoppingList(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'fixed_products')->withTimestamps();
    }

    public function shoppingListWithoutTimestamps(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'fixed_products')->withPivot(['is_active'])->where([['is_active', 1]]);
    }
}
