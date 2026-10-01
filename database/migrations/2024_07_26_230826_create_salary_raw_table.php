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
        Schema::create('salary_raw', function (Blueprint $table) {
            $table->id();
            $table->string('account', 20);
            $table->string('department', 20);
            $table->string('program', 20);
            $table->string('project', 20);
            $table->string('title');
            $table->string('category', 10);
            $table->string('uid', 20);
            $table->integer('rcd');
            $table->string('job_code', 20);
            $table->date('appointment_date');
            $table->date('termination_date');
            $table->date('posting_date');
            $table->string('earnings_code', 20);
            $table->decimal('actual', 16, 4);
            $table->decimal('commitment', 16, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_raw');
    }
};
