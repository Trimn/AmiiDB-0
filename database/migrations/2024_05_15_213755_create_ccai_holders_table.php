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
        Schema::create('ccai_holders', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->string('alias')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';

            $table->foreign('pid')->references('id')->on('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ccai_holders');
    }
};
