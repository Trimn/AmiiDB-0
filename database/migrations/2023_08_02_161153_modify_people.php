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
            $table->string('gender', 10)->nullable()->change();
            $table->string('citizenship', 100)->nullable()->change();
            $table->string('immigration', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('gender', 10)->nullable(false)->change();
            $table->string('citizenship', 100)->nullable(false)->change();
            $table->string('immigration', 10)->nullable(false)->change();
        });
    }
};
