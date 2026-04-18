<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('country', 100)->nullable();
            $table->text('biography')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
