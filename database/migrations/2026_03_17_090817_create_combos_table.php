<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->decimal('original_price', 15, 2)->default(0);
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->string('bg_from', 20)->default('#4F46E5');
            $table->string('bg_to', 20)->default('#7C3AED');
            $table->string('icon', 50)->default('psychology');
            $table->string('image', 255)->nullable();
            $table->string('badge_text', 50)->nullable();
            $table->string('button_text', 50)->nullable();
            $table->boolean('is_visible')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
