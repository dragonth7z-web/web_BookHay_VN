<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('book_id');
            $table->timestamp('added_at')->useCurrent();

            $table->primary(['user_id', 'book_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
