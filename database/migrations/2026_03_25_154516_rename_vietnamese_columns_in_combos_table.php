<?php

use Illuminate\Database\Migrations\Migration;

// NO-OP: All combos columns are now English from the start in
// 2026_03_17_090817_create_combos_table.php
return new class extends Migration
{
    public function up(): void
    {
        // no-op — columns already English in base migration
    }

    public function down(): void
    {
        // no-op
    }
};
