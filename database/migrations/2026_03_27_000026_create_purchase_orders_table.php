<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number', 20)->unique();
            $table->unsignedInteger('publisher_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->decimal('total_amount', 12, 0)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users');

            $table->index('created_at', 'idx_ngay');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
