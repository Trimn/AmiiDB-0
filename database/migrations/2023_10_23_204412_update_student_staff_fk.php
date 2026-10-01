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
            $table->dropForeign('fk_student_people');
            $table->foreign('pid', 'fk_student_people')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('fk_staff_people');
            $table->foreign('pid', 'fk_staff_people')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
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
