<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentmethod extends Model
{
    protected $table = "paymentmethods";
    protected $guarded = ['id'];
}