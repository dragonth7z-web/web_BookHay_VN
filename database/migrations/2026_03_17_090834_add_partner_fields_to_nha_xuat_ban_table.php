<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * No-op: partner fields (is_partner, partner_icon, partner_gradient) are already
     * included in 2026_03_27_000002_create_publishers_table.php (Clean_Migration).
     * This migration is kept for historical reference only.
     */
    public function up(): void
    {
        // Fields already included in Clean_Migration create_publishers_table
    }

    public function down(): void
    {
        // No-op
    }
};
