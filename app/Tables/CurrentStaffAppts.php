<?php

namespace App\Tables;

use Carbon\Carbon;
use App\Models\Terms;
use App\Models\People;
use App\Models\Fellows;
use App\Models\StaffAppt;
use App\Models\StaffType;
use App\Models\StaffSubtype;
use Illuminate\Http\Request;
use App\Models\StaffApptView;
use App\Models\Status;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class CurrentStaffAppts extends AbstractTable
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
        return StaffApptView::query();
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
            ->name('currStaffAppts')
            ->defaultSort('created_at')
            ->withGlobalSearch(columns: ['staff.person.uid', 'staff.person.last_name', 'staff.person.first_name', 'speedcode_1', 'speedcode_2', 'speedcode_3', 'staff.person._supervisor.person.last_name', 'staff.person._supervisor.person.first_name', 'staff.person.supervisor2'])
            ->selectFilter('staff.pos_type', StaffType::options(), 'Position')
            ->selectFilter('staff.subtype', StaffSubtype::options(), 'Position Subtype')
            ->selectFilter('staff.person.supervisor', Fellows::options(), 'Supervisor')
            ->selectFilter('staff.person.supervisor2', People::supervisor2(), 'Supervisor2')
            ->selectFilter('staff.active', Status::options(), 'Status')
            ->column('staff.person.uid', label: 'UID', sortable: true)
            ->column('staff.person.last_name', label: 'Last Name', sortable: true)
            ->column('staff.person.first_name', label: 'First Name', sortable: true)
            ->column('staff.person.email', label: 'Alternate email')
            ->column('staff.person.ccid', label: 'CCID', sortable: true)
            ->column('staff.person.gender', label: 'Gender', sortable: true)
            ->column('staff.person.citizenship', label: 'Citizenship', sortable: true)
            ->column('staff.person.immigration', label: 'Immigration', sortable: true)
            ->column('staff.person.amii_start', label: 'Amii start', sortable: true)
            ->column('staff.person.amii_end', label: 'Amii end', sortable: true)
            ->column('staff.person.wp_type', label: 'Permit type', sortable: true)
            ->column('staff.person.wp_start', label: 'Permit start', sortable: true)
            ->column('staff.person.wp_end', label: 'Permit end', sortable: true)
            ->column('staff.person._supervisor.person', label: "Supervisor", as: fn ($item, $appt) => ($item->first_name . " " . $item->last_name))
            ->column('staff.person.supervisor2', label: "Supervisor2")
            ->column('staff.job_title', label: 'Job Title', sortable: true)
            ->column('staff.dept', label: 'Dept', sortable: true)
            ->column('staff.position.name', label: 'Position type', sortable: true)
            ->column('staff.pos_subtype.name', label: 'Position subtype', sortable: true)
            ->column('staff.active', label: 'Status', sortable: true)
            ->column('staff.notes', label: 'Notes')
            ->column('start', label: 'Appt start', sortable: true)
            ->column('end', label: 'Appt end', sortable: true)
            ->column('speedcode_1', label: 'Speedcode 1')
            ->column('speedcode_1_prc', label: 'Speedcode 1 %')
            ->column('speedcode_2', label: 'Speedcode 2')
            ->column('speedcode_2_prc', label: 'Speecode 2 %')
            ->column('speedcode_3', label: 'Speedcode 3')
            ->column('speedcode_3_prc', label: 'Speedcode 3 %')
            ->column('hourly', as: fn($item, $appt) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('rate', as: fn($item, $appt) => (is_null($item) ? $item : Number::currency($item)), sortable: true)
            ->column('grade', sortable: true)
            ->column('step', sortable: true)
            ->column('hours', 'Hours per week', sortable: true)
            ->column('benefits', as: fn($item, $appt) => (is_null($item) ? $item : Number::currency($item)), sortable: true)
            ->column('total_pay', label: 'Total Pay', as: fn($item, $appt) => (is_null($item) ? $item : Number::currency($item)), sortable: true)
            ->column(label: 'Total/Month', as: function ($item, $appt) {
                $totalm = $appt->total_pay / (Carbon::parse($appt->start)->diffInMonths($appt->end) + 1);
                return Number::currency($totalm);
            })
            ->paginate(50)
            ->export();
    }
}
