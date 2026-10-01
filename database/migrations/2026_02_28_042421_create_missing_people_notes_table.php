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
        Schema::create('missing_people_notes', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 20)->unique();
            $table->text('notes')->nullable();
            $table->string('who')->nullable();
            $table->boolean('ignored')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missing_people_notes');
    }
};
