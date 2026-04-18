<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('badge')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
