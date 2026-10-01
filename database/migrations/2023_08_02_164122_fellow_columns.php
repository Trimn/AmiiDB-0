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
            $table->string('report_id', 100)->nullable();
            $table->string('assistant_name', 100)->nullable();
            $table->string('assistant_email', 100)->nullable();
            $table->date('start')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fellows', function (Blueprint $table) {
            $table->dropColumn(['report_id', 'assistant_name', 'assistant_email', 'start', 'notes']);
        });
    }
};
