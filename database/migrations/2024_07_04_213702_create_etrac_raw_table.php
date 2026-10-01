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
        Schema::create('etrac_raw', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->string('fund')->nullable();
            $table->string('department')->nullable();
            $table->string('program')->nullable();
            $table->string('project');
            $table->string('category')->nullable();
            $table->date('date');
            $table->decimal('actual', 16, 4)->default(0);
            $table->decimal('commitment', 16, 4)->default(0);
            $table->string('journal')->nullable();
            $table->string('supplier')->nullable();
            $table->string('voucher')->nullable();
            $table->string('rpt_id')->nullable();
            $table->string('invoice')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('po_line')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etrac_raw');
    }
};
