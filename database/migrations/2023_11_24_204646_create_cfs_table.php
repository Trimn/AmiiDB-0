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
        Schema::create('cfs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('fid');
            $table->string('speedcode', 10);
            $table->string('po', 32);
            $table->decimal('amount', 19, 4);
            $table->date('start');
            $table->date('end');
            $table->decimal('remaining', 19, 4);
            $table->enum('status', ['active', 'inactive']);
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->foreign('fid')->references('id')->on('fellows');
            $table->foreign('speedcode')->references('code')->on('speedcodes');
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cfs');
    }
};
