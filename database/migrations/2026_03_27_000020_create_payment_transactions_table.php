<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('transaction_code', 100)->unique()->nullable();
            $table->enum('method', ['vnpay', 'momo', 'bank_transfer']);
            $table->decimal('amount', 12, 0);
            $table->enum('status', ['success', 'failed', 'processing', 'refunded'])->default('processing');
            $table->json('response_data')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index('order_id', 'idx_payment_transactions_order_id');
            $table->index('status', 'idx_payment_transactions_status');
            $table->index('created_at', 'idx_payment_transactions_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
