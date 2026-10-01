<?php

use App\Models\People;
use App\Models\Supervisors;
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
        Schema::table('people', function(Blueprint $table) {
           $table->integer('supervisor')->nullable();
           $table->string('supervisor2', 255)->nullable();
           $table->foreign('supervisor')->references('id')->on('fellows')->onUpdate('cascade');
        });
        $sups = Supervisors::all();
        foreach($sups as $item) {
            $person = People::find($item->pid);
            $person->supervisor = $item->fid;
            $person->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
