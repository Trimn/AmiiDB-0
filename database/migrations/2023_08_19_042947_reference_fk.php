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
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_countries');
            $table->foreign('citizenship', 'fk_people_countries')->references('country')->on('countries')->onUpdate('cascade');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_immigration');
            $table->foreign('immigration', 'fk_people_immigration')->references('code')->on('immigration')->onUpdate('cascade');
        });

        Schema::table('student_appt', function (Blueprint $table) {
            $table->dropForeign('fk_student_appt_terms');
            $table->foreign('term', 'fk_student_appt_terms')->references('identifier')->on('terms')->onUpdate('cascade');
        });

        Schema::table('student_appt', function (Blueprint $table) {
            $table->dropForeign('fk_student_appt_appt_type');
            $table->foreign('appt_type', 'fk_student_appt_appt_type')->references('id')->on('appt_type')->onUpdate('cascade');
        });

        Schema::table('appt_payments', function (Blueprint $table) {
            $table->dropForeign('fk_appt_payments_student_appt');
            $table->foreign('appt_id', 'fk_appt_payments_student_appt')->references('id')->on('student_appt')->onUpdate('cascade');
        });

        Schema::table('appt_payments', function (Blueprint $table) {
            $table->dropForeign('fk_appt_payments_payments');
            $table->foreign('payment_id', 'fk_appt_payments_payments')->references('id')->on('payments')->onUpdate('cascade');
        });

        Schema::table('speedcodes', function (Blueprint $table) {
            $table->dropForeign('fk_speedcodes_fellows');
            $table->foreign('fellow', 'fk_speedcodes_fellows')->references('id')->on('fellows')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_countries');
            $table->foreign('citizenship', 'fk_people_countries')->references('country')->on('countries')->onUpdate('no action');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_immigration');
            $table->foreign('immigration', 'fk_people_immigration')->references('code')->on('immigration')->onUpdate('no action');
        });

        Schema::table('student_appt', function (Blueprint $table) {
            $table->dropForeign('fk_student_appt_terms');
            $table->foreign('term', 'fk_student_appt_terms')->references('identifier')->on('terms')->onUpdate('no action');
        });

        Schema::table('student_appt', function (Blueprint $table) {
            $table->dropForeign('fk_student_appt_appt_type');
            $table->foreign('appt_type', 'fk_student_appt_appt_type')->references('id')->on('appt_type')->onUpdate('no action');
        });

        Schema::table('appt_payments', function (Blueprint $table) {
            $table->dropForeign('fk_appt_payments_student_appt');
            $table->foreign('appt_id', 'fk_appt_payments_student_appt')->references('id')->on('student_appt')->onUpdate('no action');
        });

        Schema::table('appt_payments', function (Blueprint $table) {
            $table->dropForeign('fk_appt_payments_payments');
            $table->foreign('payment_id', 'fk_appt_payments_payments')->references('id')->on('payments')->onUpdate('no action');
        });

        Schema::table('speedcodes', function (Blueprint $table) {
            $table->dropForeign('fk_speedcodes_fellows');
            $table->foreign('fellow', 'fk_speedcodes_fellows')->references('id')->on('fellows')->onUpdate('no action');
        });
    }
};
