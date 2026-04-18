<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Pivot table for Combo <-> Book relationship.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_combo', function (Blueprint $table) {
            $table->unsignedInteger('combo_id');
            $table->unsignedInteger('book_id');

            $table->primary(['combo_id', 'book_id']);

            $table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_combo');
    }
};
