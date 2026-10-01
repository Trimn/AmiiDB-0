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
        Schema::table('visitor', function (Blueprint $table) {
            $table->string('fvca_category')->nullable()->change();
            $table->string('fvca_url')->nullable()->change();
            $table->boolean('letter_of_invitation')->nullable()->change();
            $table->string('airfare')->nullable()->change();
            $table->string('accomodation')->nullable()->change();
            $table->date('arrival')->nullable()->change();
            $table->date('departure')->nullable()->change();
            $table->date('on_campus')->nullable()->change();
            $table->text('workspace')->nullable()->change();
            $table->string('uofa_funding')->nullable()->change();
            $table->string('payment_amount')->nullable()->change();
            $table->string('payment_category')->nullable()->change();
            $table->boolean('welcomed')->nullable()->change();
            $table->string('welcome_url')->nullable()->change();
            $table->boolean('paf_completed')->nullable()->change();
            $table->string('paf_url')->nullable()->change();
            $table->text('notes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
