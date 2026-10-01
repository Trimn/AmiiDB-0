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
        Schema::table('rates', function (Blueprint $table) {
            $table->decimal('cs_award', 19, 4)->change();
            $table->decimal('cs_salary', 19, 4)->change();
            $table->decimal('amii_topup', 19, 4)->change();
            $table->decimal('int_idf', 19, 4)->change();
            $table->decimal('int_amii', 19, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
