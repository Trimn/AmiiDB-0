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
        Schema::create('fellow_publications', function (Blueprint $table) {
            $table->id();
            $table->integer('fid');
            $table->string('authors', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('pub_name', 255)->nullable();
            $table->date('pub_date')->nullable();
            $table->string('conf_name', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('fid')->references('id')->on('fellows');
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fellow_publications');
    }
};
