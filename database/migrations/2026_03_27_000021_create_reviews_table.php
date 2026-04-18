<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->tinyInteger('rating');
            $table->text('content')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            $table->unique(['book_id', 'user_id'], 'uk_reviews_user_book');
            $table->index(['book_id', 'status'], 'idx_sach_trang_thai');
            $table->index('user_id', 'idx_reviews_user_id');
        });

        // CHECK constraint syntax via ALTER TABLE is MySQL-specific
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
