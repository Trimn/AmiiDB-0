<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('finance_team')->where('name', 'Mike Vorona')->exists()) {
            DB::table('finance_team')->insert([
                'name' => 'Mike Vorona',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $melWesUserId = DB::table('users')
            ->where('name', 'Melanie and Wes Calvert')
            ->value('id');

        if ($melWesUserId && ! DB::table('project_team')->where('user_id', $melWesUserId)->exists()) {
            DB::table('project_team')->insert([
                'user_id' => $melWesUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('finance_team')->where('name', 'Mike Vorona')->delete();

        $melWesUserId = DB::table('users')
            ->where('name', 'Melanie and Wes Calvert')
            ->value('id');

        if ($melWesUserId) {
            DB::table('project_team')->where('user_id', $melWesUserId)->delete();
        }
    }
};
