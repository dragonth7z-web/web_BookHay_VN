<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_rankings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('week_name', 255)->nullable();
            $table->date('week_start');
            $table->date('week_end');
            $table->timestamps();

            $table->index(['week_start', 'week_end'], 'idx_weekly_rankings_range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_rankings');
    }
};
