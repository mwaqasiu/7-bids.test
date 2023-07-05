<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = "answers";
    protected $guarded = ['id'];
    
    use HasFactory;
    
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
