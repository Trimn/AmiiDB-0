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
        DB::statement("ALTER VIEW fellows_view AS
                                SELECT fellows.*, people.first_name AS first_name, people.last_name AS last_name, CONCAT(people.first_name, \" \", people.last_name) AS name 
                                FROM fellows LEFT JOIN people ON fellows.pid = people.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
