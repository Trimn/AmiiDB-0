<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff_appt', function (Blueprint $table) {
            DB::beginTransaction();
            $table->string('speedcode_1', 10);
            $table->double('speedcode_1_prc');
            $table->string('speedcode_2', 10)->nullable();
            $table->double('speedcode_2_prc')->nullable();
            $table->string('speedcode_3', 10)->nullable();
            $table->double('speedcode_3_prc')->nullable();
            $table->decimal('benefits', 19, 4)->default(0);
            $table->decimal('rate', 19, 4)->change();

            $table->foreign('speedcode_1')->references('code')->on('speedcodes')->onUpdate('cascade');
            $table->foreign('speedcode_2')->references('code')->on('speedcodes')->onUpdate('cascade');
            $table->foreign('speedcode_3')->references('code')->on('speedcodes')->onUpdate('cascade');
            DB::commit();
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
