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
        // Add PDS first while keeping PDF so data can be migrated safely.
        DB::statement("ALTER TABLE affiliate_staff MODIFY COLUMN type ENUM('MSc','PhD','Undergrad Student','PDF','PDS','Other') NOT NULL");
        DB::table('affiliate_staff')->where('type', 'PDF')->update(['type' => 'PDS']);
        DB::statement("ALTER TABLE affiliate_staff MODIFY COLUMN type ENUM('MSc','PhD','Undergrad Student','PDS','Other') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data before removing PDS from enum.
        DB::statement("ALTER TABLE affiliate_staff MODIFY COLUMN type ENUM('MSc','PhD','Undergrad Student','PDF','PDS','Other') NOT NULL");
        DB::table('affiliate_staff')->where('type', 'PDS')->update(['type' => 'PDF']);
        DB::statement("ALTER TABLE affiliate_staff MODIFY COLUMN type ENUM('MSc','PhD','Undergrad Student','PDF','Other') NOT NULL");
    }
};
