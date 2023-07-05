<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auctionwinner extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function auctionbid()
    {
        return $this->belongsTo(Auctionbid::class);
    }
    
    public function checkout() {
        return $this->belongsTo(Checkout::class);
    }
}
