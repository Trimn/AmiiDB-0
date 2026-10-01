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
        Schema::table('speedcodes', function (Blueprint $table) {
            $table->date('award_start')->nullable();
            $table->date('award_end')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speedcodes', function (Blueprint $table) {
            $table->dropColumn('award_start');
            $table->dropColumn('award_end');
            $table->dropColumn('status');
        });
    }
};
