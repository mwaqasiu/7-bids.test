<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
        'specification' => 'array',
        'imagereplaceinput' => 'array'
    ];

    // Scope

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 0)->where('expired_at', '>', now());
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 1)->where('started_at', '<', now())->where('expired_at', '>', now());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 1)->where('started_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expired_at', '<', now());
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function auctionbids(): HasMany
    {
        return $this->hasMany(Auctionbid::class);
    }

    public function auctionreviews(): HasMany
    {
        return $this->hasMany(Auctionreview::class);
    }

    public function auctionwinner(): HasOne
    {
        return $this->hasOne(Auctionwinner::class);
    }

    public function auctionWishlists(): HasMany
    {
        return $this->hasMany(Auctionwishlist::class);
    }
}
