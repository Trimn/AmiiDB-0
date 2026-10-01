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
        DB::statement('CREATE VIEW people_view AS
            SELECT *, CONCAT(first_name, " ", last_name) AS name FROM people
        ');
        DB::statement('UPDATE affiliate_staff SET sup_id = (SELECT pid FROM affiliates WHERE id = sup_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people_view');
    }
};
