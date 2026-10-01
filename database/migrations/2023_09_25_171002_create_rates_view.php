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
        DB::statement('CREATE VIEW rates_view AS SELECT *, CONCAT(term, " ", program, " ", salary_step, " ",immigration, " ", rate_type) as rate_code, (cs_award + cs_salary + amii_topup + int_idf + int_amii) as rate_amount FROM rates');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates_view');
    }
};
