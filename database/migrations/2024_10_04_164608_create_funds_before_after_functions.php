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
        DB::statement('
            CREATE FUNCTION FUNDSBEFORE ( proj VARCHAR(255), prog VARCHAR(255), opening_balance DECIMAL(19,4), start DATE )
            RETURNS DECIMAL(19,4)
            RETURN (opening_balance -
                IFNULL(IF(
                    prog IS NULL, 
                    (SELECT SUM(actual) FROM etrac WHERE project = proj COLLATE utf8mb4_general_ci AND date >= start), 
                    (SELECT SUM(actual) FROM etrac WHERE program = prog COLLATE utf8mb4_general_ci AND project = proj COLLATE utf8mb4_general_ci AND date >= start)
                ), 0)
            );
        ');
        DB::statement('
            CREATE FUNCTION FUNDSAFTER ( proj VARCHAR(255), prog VARCHAR(255), opening_balance DECIMAL(19,4), start DATE )
            RETURNS DECIMAL(19,4)
            RETURN (opening_balance -
                IFNULL(IF(
                    prog IS NULL, 
                    (SELECT SUM(actual) FROM etrac WHERE project = proj COLLATE utf8mb4_general_ci AND date >= start), 
                    (SELECT SUM(actual) FROM etrac WHERE program = prog COLLATE utf8mb4_general_ci AND project = proj COLLATE utf8mb4_general_ci AND date >= start)
                ),  0) -
                IFNULL(IF(
                    proj = "RES0042153",
                    IF(
                        prog IS NULL,
                        (SELECT SUM(commitment) FROM salary_import WHERE project = proj COLLATE utf8mb4_general_ci AND posting_date >= start), 
                        (SELECT SUM(commitment) FROM salary_import WHERE program = prog COLLATE utf8mb4_general_ci AND project = proj COLLATE utf8mb4_general_ci AND posting_date >= start)
                    ),
                    IF(
                        prog IS NULL, 
                        (SELECT SUM(commitment) FROM etrac WHERE project = proj COLLATE utf8mb4_general_ci AND date >= start), 
                        (SELECT SUM(commitment) FROM etrac WHERE program = prog COLLATE utf8mb4_general_ci AND project = proj COLLATE utf8mb4_general_ci AND date >= start)
                    )
                ), 0)
            );
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION FUNDSBEFORE');
        DB::statement('DROP FUNCTION FUNDSAFTER');
    }
};
