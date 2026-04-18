<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number', 20)->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 15);
            $table->text('shipping_address');
            $table->decimal('subtotal', 12, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('discount_amount', 12, 0)->default(0);
            $table->decimal('total', 12, 0)->default(0);
            $table->unsignedInteger('coupon_id')->nullable();
            $table->string('transaction_ref', 200)->nullable();
            $table->enum('payment_method', ['cod', 'vnpay', 'momo', 'bank_transfer'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'completed', 'cancelled', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');

            $table->index('user_id', 'idx_orders_user_id');
            $table->index('status', 'idx_orders_status');
            $table->index(['created_at', 'status'], 'idx_ngay_trang_thai');
            $table->index('total', 'idx_tong_tien');
            $table->index('transaction_ref', 'idx_ma_giao_dich');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
