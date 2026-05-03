<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_number', 20)->unique();
            $table->string('subject', 255);
            $table->text('description');
            $table->string('status', 30)->default('open');
            $table->string('priority', 20)->default('medium');
            $table->string('category', 50)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
