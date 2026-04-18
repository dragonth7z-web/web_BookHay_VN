<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_ranking_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('weekly_ranking_id');
            $table->unsignedInteger('book_id');
            $table->unsignedTinyInteger('rank')->default(1);
            $table->timestamps();

            $table->unique(['weekly_ranking_id', 'rank'], 'uq_weekly_rank_items_rank');
            $table->unique(['weekly_ranking_id', 'book_id'], 'uq_weekly_rank_items_book');
            $table->index('weekly_ranking_id', 'idx_weekly_rank_items_week');
            $table->index('book_id', 'idx_weekly_rank_items_book');

            $table->foreign('weekly_ranking_id', 'fk_weekly_rank_items_week')
                ->references('id')
                ->on('weekly_rankings')
                ->onDelete('cascade');

            $table->foreign('book_id', 'fk_weekly_rank_items_book')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_ranking_items');
    }
};
