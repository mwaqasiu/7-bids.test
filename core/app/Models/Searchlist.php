<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Searchlist extends Model
{
    protected $guarded = ['id'];

    protected $table = "searchlists";
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
