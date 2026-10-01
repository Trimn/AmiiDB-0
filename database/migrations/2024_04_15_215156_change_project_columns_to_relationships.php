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
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('projects', 'priority_id')) {
                $table->dropColumn('priority_id');
            }
            if (Schema::hasColumn('projects', 'eval_status_id')) {
                $table->dropColumn('eval_status_id');
            }
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('priority_id', false, true)->nullable();
            $table->bigInteger('eval_status_id', false, true)->nullable();

            $table->foreign('priority_id')->references('id')->on('project_priority');
            $table->foreign('eval_status_id')->references('id')->on('project_evaluation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_priority_id_foreign');
            $table->dropForeign('projects_eval_status_id_foreign');
            $table->enum('priority', ['High', 'Medium', 'Low']);
        });
    }
};
