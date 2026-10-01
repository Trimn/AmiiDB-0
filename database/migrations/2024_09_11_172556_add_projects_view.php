<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE VIEW projects_view AS
            SELECT *, FYSTART(fy_start, fy_end, 0) AS curr_fy_start, FYEND(fy_start, fy_end, 0) AS curr_fy_end FROM projects
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW projects_view');
    }
};
