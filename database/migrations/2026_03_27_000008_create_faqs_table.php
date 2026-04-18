<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question', 500);
            $table->text('answer');
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
