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
        Schema::table('student_appt', function (Blueprint $table) {
            $table->dropForeign('fk_student_appt_student');
            $table->foreign('sid', 'fk_student_appt_student')->references('id')->on('student')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('staff_appt', function (Blueprint $table) {
            $table->dropForeign('staff_appt_staff_id_foreign');
            $table->foreign('staff_id', 'staff_appt_staff_id_foreign')->references('id')->on('staff')->onDelete('cascade')->onUpdate('cascade');
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
