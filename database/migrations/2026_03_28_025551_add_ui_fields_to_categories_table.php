<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('name');
            $table->string('text_color', 50)->nullable()->after('icon');
            $table->string('bg_gradient', 255)->nullable()->after('text_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'text_color', 'bg_gradient']);
        });
    }
};
