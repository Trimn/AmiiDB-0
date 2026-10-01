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
        Schema::table('salary_import', function (Blueprint $table) {
            $table->date('appointment_date')->nullable()->change();
            $table->date('termination_date')->nullable()->change();
        });

        Schema::table('salary_raw', function (Blueprint $table) {
            $table->date('appointment_date')->nullable()->change();
            $table->date('termination_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_import', function (Blueprint $table) {
            $table->date('appointment_date')->nullable(false)->change();
            $table->date('termination_date')->nullable(false)->change();
        });

        Schema::table('salary_raw', function (Blueprint $table) {
            $table->date('appointment_date')->nullable(false)->change();
            $table->date('termination_date')->nullable(false)->change();
        });
    }
};
