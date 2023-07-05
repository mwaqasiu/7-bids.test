<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auctionwishlist extends Model
{
    protected $table = "auctionwishlists";
    protected $guarded = ['id'];
    public $timestamps = false;
    
    use HasFactory;
    
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
    
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
