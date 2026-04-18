<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->text('content');
            $table->string('author_name', 100);
            $table->string('author_avatar', 255)->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->enum('status', ['visible', 'hidden', 'pending'])->default('visible');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');

            $table->index('book_id', 'idx_comments_book_id');
            $table->index('parent_id', 'idx_cha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
