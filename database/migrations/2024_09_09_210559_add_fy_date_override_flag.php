<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('fy_override')->default(false);
        });
        DB::statement('UPDATE projects SET fy_start = "2024-05-01", fy_end = "2025-04-30", fy_override = TRUE WHERE code IN (SELECT code FROM speedcodes WHERE project = "RES0042153")');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('fy_override');
        });
    }
};
