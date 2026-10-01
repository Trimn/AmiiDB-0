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
        Schema::table('fellows', function (Blueprint $table) {
            $table->string('pub_platform_primary', 255)->nullable();
            $table->string('pub_list_location', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fellows', function (Blueprint $table) {
            $table->dropColumn('pub_platform_primary');
            $table->dropColumn('pub_list_location');
        });
    }
};
