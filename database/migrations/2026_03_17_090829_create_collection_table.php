<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * No-op: table 'collections' (formerly 'bo_suu_tap') is created by
     * 2026_03_27_000007_create_collections_table.php (Clean_Migration).
     */
    public function up(): void
    {
        // Handled by Clean_Migration create_collections_table
    }

    public function down(): void
    {
        // No-op
    }
};
