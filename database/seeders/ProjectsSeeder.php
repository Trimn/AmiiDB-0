<?php

namespace Database\Seeders;

use App\Models\ProjectEvaluation;
use App\Models\ProjectPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prios = [
            ['High', 'text-slate-900', 'bg-red-400'],
            ['Medium', 'text-slate-900', 'bg-amber-400'],
            ['Low', 'text-slate-900', 'bg-green-300'],
        ];

        foreach($prios as $prio) {
            ProjectPriority::create([
                'priority' => $prio[0],
                'text_colour' => $prio[1],
                'bg_colour' => $prio[2],
            ]);
        }

        $evals = [
            ['Done', 'text-slate-900', 'bg-green-400'],
            ['Follow Up', 'text-slate-900', 'bg-blue-400'],
            ['Investigate Further', 'text-slate-900', 'bg-yellow-400'],
            ['On Hold', 'text-slate-900', 'bg-gray-200'],
            ['To Do', 'text-slate-900', 'bg-red-400'],
        ];

        foreach($evals as $eval) {
            ProjectEvaluation::create(['status' => $eval]);
        }
    }
}
