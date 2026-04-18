<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('book_id');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('price_snapshot', 12, 0)->default(0);
            $table->timestamp('added_at')->useCurrent();

            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->unique(['cart_id', 'book_id'], 'uk_gio_sach');
            $table->index('cart_id', 'idx_gio_hang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
