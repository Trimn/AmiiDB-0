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
        Schema::create('contact_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->string('alternate_email', 255)->nullable();
            $table->enum('contact_method', ['Slack', 'UofA Email', 'Alternate Email'])->nullable();
            $table->string('meeting_email', 255)->nullable();
            $table->string('calendar_url', 255)->nullable();
            $table->text('work_schedule')->nullable();
            $table->string('doc_pref', 255)->nullable();
            $table->string('signature_url', 255)->nullable();
            $table->string('assistant_name', 255)->nullable();
            $table->string('assistant_email', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->collation = 'utf8mb4_general_ci';
            $table->charset = 'utf8mb4';

            $table->foreign('pid')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_preferences');
    }
};
