<?php

use App\Models\Projects;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Projects::whereNull('project_status')->update(['project_status' => 'active']);
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_status', 255)->nullable(false)->default('active')->change();
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
