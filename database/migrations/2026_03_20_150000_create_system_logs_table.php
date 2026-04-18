<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();

            // Loại log: auth, data, error, security
            $table->string('type', 30)->index();

            // Hành động: login, logout, login_failed, create, update, delete, error_500, ...
            $table->string('action', 50)->index();

            // Mức độ: info, warning, error, critical
            $table->string('level', 20)->default('info')->index();

            // Mô tả chi tiết
            $table->text('description');

            // Đối tượng bị tác động (nullable)
            $table->string('object_type', 100)->nullable(); // e.g. "Sach", "DonHang"
            $table->unsignedBigInteger('object_id')->nullable();

            // Dữ liệu cũ & mới (JSON) – để so sánh thay đổi
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();

            // Người thực hiện
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 100)->nullable();

            // Thông tin kỹ thuật
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('url', 500)->nullable();

            $table->timestamps();

            // Index for fast filtering
            $table->index(['type', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
