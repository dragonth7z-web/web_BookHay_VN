<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('book_id');
            $table->enum('reading_status', ['want_to_read', 'reading', 'finished'])->default('want_to_read');
            $table->unsignedInteger('current_page')->default(0);
            $table->unsignedInteger('total_pages')->default(0);
            $table->unsignedTinyInteger('personal_rating')->nullable();
            $table->text('personal_notes')->nullable();
            $table->json('quotes')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('daily_page_goal')->default(0);
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->unique(['user_id', 'book_id'], 'uk_reading_lists_user_book');
            $table->index(['user_id', 'reading_status'], 'idx_reading_lists_status');
            $table->index(['is_public', 'reading_status'], 'idx_cong_khai');
        });

        // Virtual generated column is MySQL-only
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reading_lists ADD completion_percent TINYINT UNSIGNED GENERATED ALWAYS AS (IF(total_pages > 0, LEAST(FLOOR(current_page * 100 / total_pages), 100), 0)) VIRTUAL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_lists');
    }
};
