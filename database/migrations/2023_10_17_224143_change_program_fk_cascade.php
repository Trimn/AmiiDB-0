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
        Schema::table('student', function (Blueprint $table) {
            $table->dropForeign('fk_student_program_0');
            $table->foreign('program', 'fk_student_program_0')->references('program')->on('program')->onUpdate('cascade');
        });
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign('rates_program_foreign');
            $table->foreign('program', 'rates_program_foreign')->references('program')->on('program')->onUpdate('cascade');
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
