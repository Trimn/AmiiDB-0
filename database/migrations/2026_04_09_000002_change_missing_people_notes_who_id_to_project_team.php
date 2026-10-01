<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('missing_people_notes')->update(['who_id' => null]);

        Schema::table('missing_people_notes', function (Blueprint $table) {
            $table->dropForeign(['who_id']);
            $table->foreign('who_id')->references('id')->on('project_team')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('missing_people_notes', function (Blueprint $table) {
            $table->dropForeign(['who_id']);
            $table->foreign('who_id')->references('id')->on('finance_team')->nullOnDelete();
        });
    }
};
