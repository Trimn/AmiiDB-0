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
        DB::statement(
        "CREATE FUNCTION FYSTART (fy_start DATE, fy_end DATE, year_offset INT) RETURNS DATE
                    BEGIN
                        DECLARE curr_year INT;
                        DECLARE curr_fy_end DATE;

                        SET curr_year = YEAR(CURDATE());
                        SET curr_fy_end = DATE_ADD(DATE_ADD(MAKEDATE(curr_year, 1), INTERVAL MONTH(fy_end)-1 MONTH), INTERVAL DAY(fy_end)-1 DAY);
                        IF(CURDATE() <= curr_fy_end) THEN
                            SET curr_year = curr_year - 1;
                        END IF;
                        RETURN DATE_ADD(DATE_ADD(MAKEDATE(curr_year + year_offset, 1), INTERVAL (MONTH(fy_start))-1 MONTH), INTERVAL DAY(fy_start)-1 DAY);
                    END
        ");

        DB::statement(
        "CREATE FUNCTION FYEND (fy_start DATE, fy_end DATE, year_offset INT) RETURNS DATE
                    BEGIN
                        DECLARE curr_year INT;
                        DECLARE curr_fy_end DATE;

                        SET curr_year = YEAR(CURDATE())+1;
                        SET curr_fy_end = DATE_ADD(DATE_ADD(MAKEDATE(curr_year, 1), INTERVAL MONTH(fy_end)-1 MONTH), INTERVAL DAY(fy_end)-1 DAY);
                        IF(CURDATE() > curr_fy_end) THEN
                            SET curr_year = curr_year + 1;
                        END IF;
                        RETURN DATE_ADD(DATE_ADD(MAKEDATE(curr_year + year_offset, 1), INTERVAL (MONTH(fy_end))-1 MONTH), INTERVAL DAY(fy_end)-1 DAY);
                    END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
