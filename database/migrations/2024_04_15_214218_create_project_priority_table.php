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
        Schema::create('project_priority', function (Blueprint $table) {
            $table->id();
            $table->string('priority');
            $table->string('text_colour')->nullable();
            $table->string('bg_colour')->nullable();
            $table->timestamps();
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });

        Schema::create('project_evaluation', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('text_colour')->nullable();
            $table->string('bg_colour')->nullable();
            $table->timestamps();
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_priority');
        Schema::dropIfExists('project_evaluation');
    }
};
