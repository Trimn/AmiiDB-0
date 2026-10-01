<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix HTML entities in budget_type field
        DB::table('budgets')
            ->where('budget_type', 'LIKE', '%&amp;%')
            ->update([
                'budget_type' => DB::raw("REPLACE(budget_type, '&amp;', '&')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-encode HTML entities (though this is unlikely to be needed)
        DB::table('budgets')
            ->where('budget_type', 'LIKE', '%&%')
            ->update([
                'budget_type' => DB::raw("REPLACE(budget_type, '&', '&amp;')")
            ]);
    }
};

