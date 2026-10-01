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
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_gender');
            $table->foreign('gender', 'fk_people_gender')->references('gender')->on('gender')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign('fk_people_gender');
            $table->foreign('gender', 'fk_people_gender')->references('gender')->on('gender')->onUpdate('no action');
        });
    }
};
