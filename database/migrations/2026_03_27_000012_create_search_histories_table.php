<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('keyword', 255);
            $table->unsignedSmallInteger('result_count')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('keyword', 'idx_tu_khoa');
            $table->index('user_id', 'idx_search_histories_user_id');
            $table->index('created_at', 'idx_search_histories_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
