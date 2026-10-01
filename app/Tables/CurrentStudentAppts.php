<?php

namespace App\Tables;

use Carbon\Carbon;
use NumberFormatter;
use App\Models\Terms;
use App\Models\People;
use App\Models\Status;
use App\Models\Fellows;
use App\Models\Program;
use App\Models\StudentAppt;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class CurrentStudentAppts extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return StudentAppt::query();
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
            ->name('currStudentAppts')
            ->defaultSort('created_at')
            ->withGlobalSearch(columns: ['student.person.uid', 'student.person.last_name', 'student.person.first_name', 'term', 'speedcode_1', 'speedcode_2', 'speedcode_3', 'student.person._supervisor.person.last_name', 'student.person._supervisor.person.first_name', 'student.person.supervisor2'])
            ->selectFilter('term', Terms::options())
            ->selectFilter('student.person.supervisor', Fellows::options(), 'Supervisor')
            ->selectFilter('student.person.supervisor2', People::supervisor2(), 'Supervisor2')
            ->selectFilter('student.program', Program::options(), 'Program')
            ->selectFilter('student.active', Status::options(), 'Status')
            ->column('student.person.uid', label: 'UID')
            ->column('student.person.last_name', label: 'Last Name', sortable: true)
            ->column('student.person.first_name', label: 'First Name', sortable: true)
            ->column('student.person.email', label: 'Alternate email', sortable: true)
            ->column('student.person.ccid', label: 'CCID', sortable: true)
            ->column('student.person.gender', label: 'Gender', sortable: true)
            ->column('student.person.citizenship', label: 'Citizenship', sortable: true)
            ->column('student.person.immigration', label: 'Immigration', sortable: true)
            ->column('student.person.amii_start', label: 'Amii start', sortable: true)
            ->column('student.person.amii_end', label: 'Amii end', sortable: true)
            ->column('student.person.wp_type', label: 'Permit type', sortable: true)
            ->column('student.person.wp_start', label: 'Permit start', sortable: true)
            ->column('student.person.wp_end', label: 'Permit end', sortable: true)
            ->column('student.person._supervisor.person', label: "Supervisor", as: fn ($item, $appt) => ($item->first_name . " " . $item->last_name))
            ->column('student.person.supervisor2', label: "Supervisor2")
            ->column('student.program', label: 'Program', sortable: true)
            ->column('student.phd_post', label: 'PhD Post', as: fn($item, $appt) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('student.program_start', label: 'Program start', sortable: true)
            ->column('student.dept', label: 'Dept', sortable: true)
            ->column('student.active', label: 'Status', sortable: true)
            ->column('student.curr_step', label: 'Current salary step', sortable: true)
            ->column('student.term_adj', label: 'Term adjustment', sortable: true)
            ->column('student.gf_last', label: 'GF Last', sortable: true)
            ->column('student.notes', label: 'Notes')
            ->column('term', sortable: true)
            ->column('start', label: 'Appt start', sortable: true)
            ->column('end', label: 'Appt end', sortable: true)
            ->column('appt.name', label: 'Appt type')
            ->column('eform')
            ->column('speedcode_1', label: 'Speedcode 1')
            ->column('speedcode_1_prc', label: 'Speedcode 1 %')
            ->column('speedcode_2', label: 'Speedcode 2')
            ->column('speedcode_2_prc', label: 'Speecode 2 %')
            ->column('speedcode_3', label: 'Speedcode 3')
            ->column('speedcode_3_prc', label: 'Speedcode 3 %')
            ->column('rate_view.rate_code', label: 'Rate Code')
            ->column('rate_view.rate_amount', label: 'Rate Amount', as: fn($item, $appt) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('rate_adj', label: 'Rate Adjustment', as: fn($item, $appt) => (is_null($item) ? $item : Number::currency($item)), sortable: true)
            ->column(label: 'Total Amount', as: function ($item, $appt) {
                $rate = 0;
                if(!is_null($appt->rate_view)) {
                    $rate = $appt->rate_view->rate_amount;
                }
                return Number::currency($rate + $appt->rate_adj);
            })
            ->column(label: 'Total/Month', as: function($item, $appt) {
                $rate = 0;
                if(!is_null($appt->rate_view)) {
                    $rate = $appt->rate_view->rate_amount;
                }
                return Number::currency(($rate + $appt->rate_adj) / (Carbon::parse($appt->start)->diffInMonths($appt->end) + 1));
            })
            ->paginate(50)
            ->export();
        
    }
}
