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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->date('start');
            $table->date('end');
            $table->decimal('amount', 19, 4);
            $table->string('code', 10);
            $table->string('type', 50);
            $table->timestamps();
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';

            $table->foreign('code')->references('code')->on('speedcodes');
            $table->foreign('pid')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};


