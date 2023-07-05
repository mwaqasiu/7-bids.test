<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bids()
    {
        return $this->hasManyThrough(Bid::class, Product::class);
    }
    
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }
    
    public function auctionreviews()
    {
        return $this->hasMany(Auctionreview::class);
    }

    public function auctionbids()
    {
        return $this->hasManyThrough(Auctionbid::class, Auction::class);
    }

}
