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
        Schema::table('budgets', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['pid']);
            
            // Make pid nullable
            $table->integer('pid')->nullable()->change();
            
            // Add description field
            $table->string('description', 255)->nullable()->after('pid');
            
            // Re-add foreign key constraint but make it nullable
            $table->foreign('pid')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['pid']);
            
            // Remove description
            $table->dropColumn('description');
            
            // Make pid required again
            $table->integer('pid')->nullable(false)->change();
            
            // Re-add foreign key
            $table->foreign('pid')->references('id')->on('people');
        });
    }
};

