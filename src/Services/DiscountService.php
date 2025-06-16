<?php

namespace DiscountSystem\Services;

use DiscountSystem\Models\Discount;
use DiscountSystem\Models\DiscountUsage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DiscountService
{
    public function apply(string $code, int $userId, int $orderAmount): array
    {
        $discount = Discount::where('code', $code)->first();

        if(!$discount){
            return [
                'error' => true,
                'message' => __('discount::messages.not_found'),
                'status' => 404,
            ];
        }

        // Time validity
        if ($discount->start_at && now()->lt($discount->start_at)) {
            throw ValidationException::withMessages(['code' =>  __('discount::messages.not_active')]);
        }

        if ($discount->end_at && now()->gt($discount->end_at)) {
            throw ValidationException::withMessages(['code' => __('discount::messages.expired')]);
        }

        // Order amount condition
        if ($discount->min_order_amount && $orderAmount < $discount->min_order_amount) {
            throw ValidationException::withMessages(['code' => __('discount::messages.order_too_small')]);
        }

        // Usage count limit
        if ($discount->usage_limit !== null &&
            $discount->usages()->count() >= $discount->usage_limit) {
            throw ValidationException::withMessages(['code' => __('discount::messages.usage_limit')]);
        }

        // Per-user limit
        if ($discount->once_per_user &&
            DiscountUsage::where('discount_id', $discount->id)
                ->where('user_id', $userId)->exists()) {
            throw ValidationException::withMessages(['code' => __('discount::messages.already_used')]);
        }

        // Specific users
        if ($discount->specific_user_ids && !in_array($userId, $discount->specific_user_ids)) {
            throw ValidationException::withMessages(['code' => __('discount::messages.not_eligible')]);
        }

        // Calculate
        $discountAmount = $discount->type === Discount::TYPE_PERCENT
            ? ($orderAmount * $discount->value / 100)
            : $discount->value;

        return [
            'discount' => $discount,
            'amount' => (int) round($discountAmount),
        ];
    }

    public function recordUsage(int $discount_id, int $userId): void
    {

        DiscountUsage::create([
            'discount_id' => $discount_id,
            'user_id' => $userId,
            'used_at' => now(),
        ]);
    }

    public function generateCode(string $typePrefix): string
    {
        do {
            $code = $typePrefix . strtoupper(\Str::random(6));
        } while (Discount::where('code', $code)->exists());

        return $code;
    }
}

