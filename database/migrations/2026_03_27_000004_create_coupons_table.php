<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->enum('type', ['percentage', 'fixed_amount'])->default('percentage');
            $table->decimal('value', 12, 0);
            $table->decimal('max_discount', 12, 0)->default(0);
            $table->decimal('min_order_amount', 12, 0)->default(0);
            $table->integer('usage_limit')->default(0);
            $table->integer('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['active', 'paused', 'expired'])->default('active');
            $table->softDeletes();

            $table->index('status', 'idx_coupons_status');
            $table->index(['starts_at', 'expires_at'], 'idx_thoi_gian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
