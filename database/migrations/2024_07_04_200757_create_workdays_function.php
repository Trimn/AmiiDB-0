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
        DB::select("CREATE FUNCTION WORKDAYS ( end DATE, start DATE )
        RETURNS INT
        BEGIN
            RETURN 5 * (DATEDIFF(end, start) DIV 7) + MID('0123455401234434012332340122123401101234000123450', 7 * WEEKDAY(start) + WEEKDAY(end) + 1, 1);
        END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::select("DROP FUNCTION IF EXISTS WORKDAYS");
    }
};
