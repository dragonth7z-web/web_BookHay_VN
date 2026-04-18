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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('ui_icon', 50)->nullable()->after('name');
            $table->string('theme_class', 50)->nullable()->after('ui_icon');
            $table->text('overlay_gradient')->nullable()->after('theme_class');
            $table->string('glow_color', 50)->nullable()->after('overlay_gradient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['ui_icon', 'theme_class', 'overlay_gradient', 'glow_color']);
        });
    }
};
