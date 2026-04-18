<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// The base `users` table is created by 0001_01_01_000000_create_users_table.php.
// This migration adds the application-specific columns that are not in the Laravel default schema.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 15)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 255)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->default('other')->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedTinyInteger('role_id')->default(2)->after('gender');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended'])->default('active')->after('role_id');
            }
            if (!Schema::hasColumn('users', 'loyalty_points')) {
                $table->unsignedInteger('loyalty_points')->default(0)->after('status');
            }
            if (!Schema::hasColumn('users', 'total_spent')) {
                $table->decimal('total_spent', 15, 0)->default(0)->after('loyalty_points');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('status', 'idx_users_status');
            });
        } catch (\Exception $e) { /* index already exists */ }

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('role_id', 'idx_users_role_id');
            });
        } catch (\Exception $e) { /* index already exists */ }

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('role_id')->references('id')->on('roles');
            });
        } catch (\Exception $e) { /* FK already exists */ }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['role_id']); } catch (\Exception $e) {}
            try { $table->dropIndex('idx_users_status'); } catch (\Exception $e) {}
            try { $table->dropIndex('idx_users_role_id'); } catch (\Exception $e) {}
            $table->dropColumn(['phone', 'avatar', 'date_of_birth', 'gender', 'role_id', 'status', 'loyalty_points', 'total_spent', 'deleted_at']);
        });
    }
};
