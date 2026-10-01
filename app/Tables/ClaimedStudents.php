<?php

namespace App\Tables;

use App\Models\Terms;
use App\Models\StudentAppt;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ClaimedStudents extends AbstractTable
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
        return Auth::check();
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $appts = StudentAppt::query();
        // $term = request()->query('term');
        // if($term) {
        //     $appts->where('term', '=', $term);
        // }
        return $appts;
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
            ->name('claimedStudents')
            ->selectFilter('term', Terms::options())
            ->column('student.person.uid', 'UID', sortable: true)
            ->column('student.person.last_name', 'Last Name', sortable: true)
            ->column('student.person.first_name', 'First Name', sortable: true)
            ->column('student.person.ccid', 'CCID', sortable: true)
            ->column('student.person.supervisor_view.name', 'Primary Supervisor', sortable: true)
            ->column('student.person.supervisor2', 'Secondary Supervisor', sortable: true)
            ->column('term', sortable: true)
            ->column('appt.name', 'Appointment Type', sortable: true)
            ->column('start', 'Appointment Start Date', sortable: true)
            ->column('end', 'Appointment End Date', sortable: true)
            ->column('rate', 'Total Amount Paid', as: fn($item, $row) => Number::currency($row->rate ? $row->rate_view->rate_amount + $row->rate_adj : 0))
            ->paginate()
            ->export();
    }
}
