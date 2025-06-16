<?php

use DiscountSystem\Models\Discount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration{

    public function up(){
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', Discount::TYPES);
            $table->decimal('value', 8, 2);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('one_time_use')->default(false);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('min_order_amount')->nullable();
            $table->boolean('once_per_user')->default(false);
            $table->json('specific_user_ids')->nullable();
            $table->string('prefix')->nullable();
            $table->timestamps();
        });
    }
};
