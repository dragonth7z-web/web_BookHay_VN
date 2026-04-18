<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * No-op: flash_sale_items table is created by
     * 2026_03_27_000031_create_flash_sale_items_table.php (Clean_Migration)
     * which runs after books table exists.
     */
    public function up(): void
    {
        // Handled by Clean_Migration
    }

    public function down(): void
    {
        // No-op
    }
};
