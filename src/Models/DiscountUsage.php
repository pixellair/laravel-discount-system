<?php

namespace DiscountSystem\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountUsage extends Model
{
    public $timestamps = false;
    protected $fillable = ['discount_id', 'user_id', 'used_at'];
}


