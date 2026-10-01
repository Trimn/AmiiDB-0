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
        Schema::dropIfExists('rates');
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('term', 5);
            $table->string('program', 10);
            $table->integer('program_year');
            $table->integer('salary_step');
            $table->enum('rate_type', ['CS', 'Amii', 'Top', 'Post']);
            $table->string('immigration', 10);
            $table->float('cs_award');
            $table->float('cs_salary');
            $table->float('amii_topup');
            $table->float('int_idf');
            $table->float('int_amii');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('term')->references('identifier')->on('terms');
            $table->foreign('program')->references('program')->on('program');
            $table->foreign('immigration')->references('code')->on('immigration');

            $table->collation = 'utf8mb4_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('program', 10);
            $table->integer('level');
            $table->float('amount');
            $table->float('topup');
            $table->tinyInteger('intl');
            $table->tinyInteger('post_candidacy');
            $table->timestamps();

            $table->foreign('program')->references('program')->on('program');

            $table->collation = 'utf8mb4_general_ci';
        });
    }
};
