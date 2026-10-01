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
        Schema::table('fellows', function (Blueprint $table) {
            $table->boolean('ccai_chair')->default(false);
            $table->date('ccai_start')->nullable();
            $table->date('ccai_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fellows', function (Blueprint $table) {
            $table->dropColumn(['ccai_chair', 'ccai_start', 'ccai_end']);
        });
    }
};
