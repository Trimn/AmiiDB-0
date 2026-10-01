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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->string('title', 255)->nullable();
            $table->string('affiliation', 255)->nullable();
            $table->string('alternate_email', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('pid')->references('id')->on('people');
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
