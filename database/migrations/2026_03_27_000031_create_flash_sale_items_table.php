<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('flash_sale_id');
            $table->unsignedInteger('book_id');
            $table->decimal('flash_price', 12, 0)->default(0);
            $table->unsignedTinyInteger('display_order')->default(1);
            $table->timestamps();

            $table->unique(['flash_sale_id', 'display_order'], 'uq_flash_sale_items_pos');
            $table->unique(['flash_sale_id', 'book_id'], 'uq_flash_sale_items_book');
            $table->index('flash_sale_id', 'idx_flash_sale_items_sale');
            $table->index('book_id', 'idx_flash_sale_items_book');

            $table->foreign('flash_sale_id', 'fk_flash_sale_items_sale')
                ->references('id')
                ->on('flash_sales')
                ->onDelete('cascade');

            $table->foreign('book_id', 'fk_flash_sale_items_book')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
