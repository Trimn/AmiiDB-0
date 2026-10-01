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
        Schema::table('status', function (Blueprint $table) {
            $table->string('description', 100)->nullable()->change();
        });
        Schema::table('student', function (Blueprint $table) {
            $table->dropForeign('fk_student_status');
            $table->foreign('active', 'fk_student_status')->references('name')->on('status')->onUpdate('cascade');
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('fk_staff_status');
            $table->foreign('active', 'fk_staff_status')->references('name')->on('status')->onUpdate('cascade');
        });
        Schema::table('visitor', function (Blueprint $table) {
            $table->dropForeign('fk_visitor_status');
            $table->foreign('active', 'fk_visitor_status')->references('name')->on('status')->onUpdate('cascade');
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
