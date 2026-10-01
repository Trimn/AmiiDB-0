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
        Schema::table('etrac', function (Blueprint $table) {
            $table->string('fund')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('program')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etrac', function (Blueprint $table) {
            $table->string('fund')->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
            $table->string('program')->nullable(false)->change();
        });
    }
};
