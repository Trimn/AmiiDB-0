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
        Schema::create('staff_appt', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_id');
            $table->date('start');
            $table->date('end');
            $table->float('rate');
            $table->integer('grade')->nullable();
            $table->integer('step')->nullable();
            $table->float('hours');
            $table->tinyInteger('hourly');
            $table->collation = 'utf8mb4_general_ci';

            $table->foreign('staff_id')->references('id')->on('staff');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_appt');
    }
};
