<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminanswerNotification extends Model
{
    use HasFactory;

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
