<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['order', 'promotion', 'system'])->default('system');
            $table->string('title', 255);
            $table->text('content');
            $table->string('url', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'is_read'], 'idx_nguoi_dung_doc');
            $table->index('created_at', 'idx_notifications_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
