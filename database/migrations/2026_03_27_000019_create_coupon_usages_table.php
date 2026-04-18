<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('order_id');
            $table->timestamp('used_at')->useCurrent();

            $table->unique(['coupon_id', 'user_id'], 'uk_user_voucher');

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index('user_id', 'idx_coupon_usages_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
