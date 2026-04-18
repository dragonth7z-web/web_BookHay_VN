<?php

use Illuminate\Database\Migrations\Migration;

// NO-OP: All homepage dynamic fields (badge_text, badge_color on categories,
// badge_text + button_text on combos) are now included in their respective
// base create_* migrations.
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
