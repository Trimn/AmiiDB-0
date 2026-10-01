<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('budget_type', 100)->nullable()->after('type');
        });

        // Set default value for existing entries
        DB::table('budgets')->whereNull('budget_type')->update(['budget_type' => 'Salaries & Benefits']);

        // Now make the column required with default
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('budget_type', 100)->nullable(false)->default('Salaries & Benefits')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('budget_type');
        });
    }
};

