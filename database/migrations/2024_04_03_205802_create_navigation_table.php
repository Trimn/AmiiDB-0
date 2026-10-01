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
        Schema::create('nav_categories', function (Blueprint $table) {
            $table->string('category')->primary();
            $table->integer('order');
            $table->timestamps();
        });
        Schema::create('navigation', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('category')->nullable();
            $table->integer('order')->nullable();
            $table->string('permission');
            $table->timestamps();

            $table->foreign('category')->references('category')->on('nav_categories')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation');
        Schema::dropIfExists('nav_categories');
    }
};
