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
        DB::statement("CREATE VIEW staff_appt_view AS SELECT *, ((CASE WHEN hourly = 1 THEN rate * (hours / 5) ELSE rate / 260 END) * ((5 * (DATEDIFF(end, start) DIV 7) + MID('0123455401234434012332340122123401101234000123450', 7 * WEEKDAY(start) + WEEKDAY(end) + 1, 1))) + benefits) as total_pay FROM staff_appt");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS staff_appt_view");
    }
};
