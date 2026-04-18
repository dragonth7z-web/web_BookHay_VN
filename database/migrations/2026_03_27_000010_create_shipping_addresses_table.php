<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 15);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('address_detail', 255);
            $table->boolean('is_default')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id', 'idx_shipping_addresses_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
