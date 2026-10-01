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
        Schema::create('finance_team', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->timestamps();
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->decimal('total_award', 19, 4)->nullable();
            $table->decimal('funds_before', 19, 4)->nullable();
            $table->decimal('funds_after', 19, 4)->nullable();
            $table->decimal('future_funding', 19, 4)->nullable();
            $table->string('project_status', 255)->nullable();
            $table->decimal('percent_spent', 8,4)->nullable();
            $table->string('oe_status', 255)->nullable();
            $table->decimal('auth_oe_amount', 19, 4)->nullable();
            $table->date('oe_auth_end')->nullable();
            $table->string('oe_req_status', 255)->nullable();
            $table->boolean('financial_report')->nullable();
            $table->boolean('supervisor_review')->nullable();
            $table->bigInteger('who_assigned', false, true)->nullable();
            $table->enum('priority', ['High', 'Medium', 'Low'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('code')->references('code')->on('speedcodes');
            $table->foreign('who_assigned')->references('id')->on('finance_team');
            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_team');
        Schema::dropIfExists('projects');
    }
};
