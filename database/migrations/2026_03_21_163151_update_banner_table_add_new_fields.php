<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NO-OP: All banner columns (badge_text, nut_bam_text, position, timestamps, etc.)
// are already included in Clean_Migration 2026_03_27_000028_create_banner_table.php
// This migration is kept as a no-op to avoid breaking the migration history.
return new class extends Migration
{
    public function up(): void
    {
        // no-op
    }

    public function down(): void
    {
        // no-op
    }
};
