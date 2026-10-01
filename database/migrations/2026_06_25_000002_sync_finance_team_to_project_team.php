<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('project_team')) {
            return;
        }

        $now = now();
        $existingUserIds = DB::table('project_team')->pluck('user_id')->all();

        foreach (DB::table('finance_team')->orderBy('id')->get() as $member) {
            $userId = DB::table('users')->where('name', $member->name)->value('id');

            if (! $userId || in_array($userId, $existingUserIds, true)) {
                continue;
            }

            DB::table('project_team')->insert([
                'user_id' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $existingUserIds[] = $userId;
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('project_team')) {
            return;
        }

        $financeTeamNames = DB::table('finance_team')->pluck('name');

        $userIds = DB::table('users')
            ->whereIn('name', $financeTeamNames)
            ->pluck('id');

        DB::table('project_team')->whereIn('user_id', $userIds)->delete();
    }
};
