<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('logo', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_partner')->default(false);
            $table->string('partner_icon', 255)->nullable();
            $table->string('partner_gradient', 255)->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
