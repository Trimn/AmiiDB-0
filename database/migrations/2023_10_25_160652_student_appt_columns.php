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
            $table->dropColumn('level');
            $table->dropColumn('amount');
            $table->string('speedcode_1', 10);
            $table->double('speedcode_1_prc');
            $table->string('speedcode_2', 10)->nullable();
            $table->double('speedcode_2_prc')->nullable();
            $table->string('speedcode_3', 10)->nullable();
            $table->double('speedcode_3_prc')->nullable();
            $table->bigInteger('rate', false, true)->nullable();
            $table->decimal('rate_adj', 19, 4)->nullable();

            $table->foreign('speedcode_1')->references('code')->on('speedcodes')->onUpdate('cascade');
            $table->foreign('speedcode_2')->references('code')->on('speedcodes')->onUpdate('cascade');
            $table->foreign('speedcode_3')->references('code')->on('speedcodes')->onUpdate('cascade');
            $table->foreign('rate')->references('id')->on('rates')->onUpdate('cascade');
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
