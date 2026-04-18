<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->string('badge_text', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('button_text', 255)->nullable();
            $table->enum('position', ['home_main', 'home_mini', 'home_gift', 'Slider', 'Sidebar', 'Footer'])->default('Slider');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `banner` COMMENT = 'Slider / Banner quảng cáo'");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
