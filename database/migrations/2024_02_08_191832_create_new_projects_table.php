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
        Schema::create('new_projects', function (Blueprint $table) {
            $table->id();
            $table->string('holder', 255);
            $table->string('project_id', 20)->nullable();
            $table->date('award_start')->nullable();
            $table->date('award_end')->nullable();
            $table->string('code', 10);
            $table->string('title', 255)->nullable();
            $table->decimal('total_award', 19, 4)->nullable();
            $table->decimal('funds_before', 19, 4)->nullable();
            $table->decimal('funds_after', 19, 4)->nullable();
            $table->string('project_status', 100)->nullable();
            $table->double('percent_spent')->nullable();
            $table->string('oe_status', 100)->nullable();
            $table->decimal('auth_oe_amount', 19, 4)->nullable();
            $table->date('oe_auth_end')->nullable();
            $table->string('oe_req_status', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_projects');
    }
};
