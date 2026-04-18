<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address', 45);
            $table->string('device', 500)->nullable();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->string('failure_reason', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id', 'idx_login_histories_user_id');
            $table->index('created_at', 'idx_login_histories_created_at');
            $table->index('ip_address', 'idx_ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
