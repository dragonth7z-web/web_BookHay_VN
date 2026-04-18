<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('badge_text', 50)->nullable();
            $table->string('badge_color', 20)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');

            $table->index(['is_visible', 'sort_order'], 'idx_hien_thi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
