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
        Schema::create('visitor', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->date('dob')->nullable();
            $table->boolean('ccid_requested')->nullable();
            $table->date('ccid_req_date')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Coming'])->nullable();
            $table->string('speedcode', 10);
            $table->string('fvca_category');
            $table->string('fvca_url');
            $table->boolean('letter_of_invitation');
            $table->string('airfare');
            $table->string('accomodation');
            $table->date('arrival');
            $table->date('departure');
            $table->date('on_campus');
            $table->text('workspace');
            $table->string('uofa_funding');
            $table->string('payment_amount');
            $table->string('payment_category');
            $table->boolean('welcomed');
            $table->string('welcome_url');
            $table->boolean('paf_completed');
            $table->string('paf_url');
            $table->text('notes');
            $table->timestamps();
            
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';

            $table->foreign('pid')->references('id')->on('people');
            $table->foreign('speedcode')->references('code')->on('speedcodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor');
    }
};
