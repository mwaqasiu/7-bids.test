<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = "wishlists";
    protected $guarded = ['id'];
    public $timestamps = false;
    
    use HasFactory;
    
    public function product()
    {
        return $this->belongsTo(Product::class);
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
