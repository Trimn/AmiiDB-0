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
        Schema::create('sba', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('pid');
            $table->string('reason_code', 3);
            $table->text('reason');
            $table->string('debit_speedcode', 10);
            $table->string('credit_speedcode', 10);
            $table->decimal('amount', 19, 4);
            $table->string('budget_holder', 255);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('pid')->references('id')->on('people');
            $table->foreign('debit_speedcode')->references('code')->on('speedcodes');
            $table->foreign('credit_speedcode')->references('code')->on('speedcodes');
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sba');
    }
};
