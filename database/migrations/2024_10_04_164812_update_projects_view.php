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
        DB::statement('DROP VIEW IF EXISTS projects_view');
        DB::statement('CREATE VIEW projects_view AS
            SELECT 
                projects.id AS proj_id,
                projects.code AS view_code,
                IF(project = "RES0042153", FUNDSBEFORE(project, program, opening_balance, fy_start), projects.funds_before) AS funds_before_calc,
                IF(project = "RES0042153", FUNDSAFTER(project, program, opening_balance, fy_start), projects.funds_after) AS funds_after_calc,
                FYSTART(fy_start, fy_end, 0) AS curr_fy_start, 
                FYEND(fy_start, fy_end, 0) AS curr_fy_end 
            FROM projects
                LEFT JOIN speedcodes ON speedcodes.code = projects.code
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
