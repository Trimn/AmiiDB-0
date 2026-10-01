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
        Schema::create('affiliate_staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->integer('pid');
            $table->enum('type', ['MSc', 'PhD', 'Undergrad Student', 'PDF', 'Other']);
            $table->date('start');
            $table->date('end')->nullable();
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
        Schema::dropIfExists('affiliate_staff');
    }
};
