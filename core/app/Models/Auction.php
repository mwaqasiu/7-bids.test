<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function scopePending()
    {
        return $this->where('status', 0)->where('expired_at', '>', now());
    }

    public function scopeLive()
    {
        return $this->where('status', 1)->where('started_at', '<', now())->where('expired_at', '>', now());
    }

    public function scopeUpcoming()
    {
        return $this->where('status', 1)->where('started_at', '>', now());
    }

    public function scopeExpired()
    {
        return $this->where('expired_at', '<', now());
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auctionbids()
    {
        return $this->hasMany(Auctionbid::class);
    }

    public function auctionreviews()
    {
        return $this->hasMany(Auctionreview::class);
    }

    public function auctionwinner()
    {
        return $this->hasOne(Auctionwinner::class);
    }
}
