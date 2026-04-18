<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('book_id');
            $table->unsignedInteger('quantity');
            $table->decimal('cost_price', 12, 0);

            $table->primary(['purchase_order_id', 'book_id']);

            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
