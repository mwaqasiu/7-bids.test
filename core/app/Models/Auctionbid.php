<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auctionbid extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function auctionwinner()
    {
        return $this->hasOne(Auctionwinner::class);
    }
}
