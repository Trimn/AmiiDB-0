<?php

namespace App\Tables;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use App\Models\Salary;

class MissingSalaryPeopleTable extends AbstractTable
{
    protected bool $showIgnored;

    public function __construct(bool $showIgnored = false)
    {
        $this->showIgnored = $showIgnored;
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $request->user()->hasRole('super admin') || $request->user()->hasRole('admin');
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Salary::query()
            ->from('salary_import as si')
            ->select(
                DB::raw("LPAD(si.uid, 7, '0') as uid"),
                DB::raw('MAX(si.name) as name'),
                'si.project',
                'si.program',
                'sp.code as speedcode',
                DB::raw('MIN(si.posting_date) as first_date_seen'),
                DB::raw('SUM(si.actual) as total_actual'),
                DB::raw('SUM(si.commitment) as total_commitment'),
                'mpn.notes',
                'mpn.who_id',
                DB::raw('ptu.name as who_name'),
                'mpn.ignored'
            )
            ->leftJoinSub(
                DB::table('speedcodes as s')
                    ->join('projects as p', 's.code', '=', 'p.code')
                    ->select('s.project', 'p.program', 's.code'),
                'sp',
                function($join) {
                    $join->on(DB::raw('sp.project COLLATE utf8mb4_general_ci'), '=', DB::raw('si.project COLLATE utf8mb4_general_ci'))
                         ->on(DB::raw('sp.program COLLATE utf8mb4_general_ci'), '=', DB::raw('si.program COLLATE utf8mb4_general_ci'));
                }
            )
            ->leftJoin('missing_people_notes as mpn', DB::raw("LPAD(si.uid, 7, '0')"), '=', 'mpn.uid')
            ->leftJoin('project_team as pt', 'mpn.who_id', '=', 'pt.id')
            ->leftJoin('users as ptu', 'pt.user_id', '=', 'ptu.id')
            ->whereNotNull('sp.code')
            ->whereRaw("LPAD(si.uid, 7, '0') COLLATE utf8mb4_general_ci NOT IN (SELECT LPAD(uid, 7, '0') COLLATE utf8mb4_general_ci FROM people WHERE uid IS NOT NULL)")
            ->where('sp.code', '!=', 'ZAA9I')
            ->where(function($query) {
                if ($this->showIgnored) {
                    $query->where('mpn.ignored', true);
                } else {
                    $query->where('mpn.ignored', false)->orWhereNull('mpn.ignored');
                }
            })
            ->groupBy(DB::raw("LPAD(si.uid, 7, '0')"), 'si.project', 'si.program', 'sp.code', 'mpn.notes', 'mpn.who_id', 'ptu.name', 'mpn.ignored');
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->name($this->showIgnored ? 'missingSalaryPeopleIgnored' : 'missingSalaryPeople')
            ->rowModal(fn($item) => route('people.missing_salary_transactions', [
                'uid' => $item->uid, 
                'project' => $item->project,
                'program' => $item->program,
            ]))
            ->column('uid', 'UID', sortable: true, searchable: true)
            ->column('name', 'Name', sortable: true, searchable: true)
            ->column('project', 'Project', sortable: true, searchable: true)
            ->column('program', 'Program', sortable: true, searchable: true)
            ->column('speedcode', 'Speedcode', sortable: true, searchable: true)
            ->column('first_date_seen', 'First Date Seen', sortable: true)
            ->column('total_actual', 'Total Actual', sortable: true, as: fn($total_actual) => Number::currency($total_actual ?? 0))
            ->column('total_commitment', 'Total Commitment', sortable: true, as: fn($total_commitment) => Number::currency($total_commitment ?? 0))
            ->column('notes', 'Notes')
            ->column('who_name', 'Who', sortable: true)
            ->column('actions', 'Actions');
    }
}
