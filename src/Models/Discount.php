<?php

namespace DiscountSystem\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'start_at', 'end_at',
        'one_time_use', 'usage_limit', 'min_order_amount',
        'once_per_user', 'specific_user_ids', 'prefix'
    ];

    protected $casts = [
        'specific_user_ids' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    const TYPE_PERCENT = 'percent';
    const TYPE_AMOUNT = 'amount';
    const TYPES = [
        self::TYPE_PERCENT,
        self::TYPE_AMOUNT
    ];

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }
}

