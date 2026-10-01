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
        DB::statement("CREATE VIEW affiliates_view AS
                                SELECT affiliates.*, people.first_name AS first_name, people.last_name AS last_name, CONCAT(people.first_name, \" \", people.last_name) AS name 
                                FROM affiliates LEFT JOIN people ON affiliates.pid = people.id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates_view');
    }
};
