<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->timestamps();

            $table->index(['start_date', 'end_date'], 'idx_flash_sales_range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
