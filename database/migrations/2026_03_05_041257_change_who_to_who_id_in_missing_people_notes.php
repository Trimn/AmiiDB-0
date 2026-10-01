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
        Schema::table('missing_people_notes', function (Blueprint $table) {
            $table->dropColumn('who');
            $table->unsignedBigInteger('who_id')->nullable()->after('notes');
            $table->foreign('who_id')->references('id')->on('finance_team')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('missing_people_notes', function (Blueprint $table) {
            $table->dropForeign(['who_id']);
            $table->dropColumn('who_id');
            $table->string('who')->nullable()->after('notes');
        });
    }
};
