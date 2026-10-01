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
            $table->string('post_uofa_employer')->nullable()->after('supervisor2');
            $table->timestamp('post_uofa_employer_updated_at')->nullable()->after('post_uofa_employer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(['post_uofa_employer', 'post_uofa_employer_updated_at']);
        });
    }
};
