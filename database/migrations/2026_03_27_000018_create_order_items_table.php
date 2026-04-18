<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('book_id');
            $table->string('book_title_snapshot', 255);
            $table->string('book_image_snapshot', 255)->nullable();
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 0);
            $table->decimal('subtotal', 12, 0);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books');

            $table->index('order_id', 'idx_order_items_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
