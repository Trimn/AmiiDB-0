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
            $table->string('project', 20)->nullable();
            $table->integer('combo_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speedcodes', function (Blueprint $table) {
            $table->dropColumn(['projects', 'combo_code']);
        });
    }
};
