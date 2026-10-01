<?php

namespace App\Tables;

use App\Models\Salary;
use App\Models\Projects;
use App\Models\StaffAppt;
use Illuminate\Http\Request;
use App\Models\StaffApptView;
use App\Models\ProjectImports;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ForecastingStaff extends AbstractTable
{
    private $project;
    private $date;
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Projects $project, $date)
    {
        $this->project = $project;
        $this->date = $date;
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
        // $lastImport = ProjectImports::select('created_at')->orderByDesc('created_at')->limit(1)->first()['created_at'];
        // $start = $this->project['funds_before'] ?? $this->project['total_award'];
        // $start_date = $lastImport->format('Y-m-d');
        // if(Salary::count() == 0) {
        //     $start_date = $lastImport->format('Y-m-d'); 
        // }
        // else {
        //     $start_date = Salary::query()->where('actual', '>', 0)->orderByDesc('posting_date')->limit(1)->first()['posting_date']->addDays(1)->format('Y-m-d');
        // }
        // return StaffApptView::query()
        //     ->select('*', DB::raw("'$this->date' AS reporting_end"), DB::raw("'$start_date' AS reporting_start"), DB::raw("WORKDAYS(end, start) AS days_in_appt"), DB::raw("WORKDAYS(IF('$this->date' < end, '$this->date', end), IF('$start_date' > start, '$start_date', start)) AS days_in_range"), DB::raw("WORKDAYS(IF('$this->date' < end, '$this->date', end), IF('$start_date' > start, '$start_date', start)) * (total_pay / WORKDAYS(end, start)) AS remaining"))
        //     ->where('end', '>', $start_date)
        //     ->where('start', '<', $this->date)
        //     ->where(function ($query) {
        //         $query->where('speedcode_1', '=', $this->project->code)->orWhere('speedcode_2', '=', $this->project->code)->orWhere('speedcode_3', '=', $this->project->code);
        //     });
        return Projects::staff_commitment_query($this->project, $this->date);
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
            ->name('fellows_staff')
            ->column('staff.person.last_name', 'Last Name', sortable: true)
            ->column('staff.person.first_name', 'First Name', sortable: true)
            ->column('staff.person.uid', 'UID', sortable: true)
            ->column(label: 'Actuals', as: fN($item, $row) => Number::currency(Salary::getPersonActuals($this->project->speedcode['project'], $this->project['program'], $row->staff->person['uid'], '2023-04-01', $row['reporting_end'])))
            ->column('remaining', 'Est commitment', as: fn($item, $row) => Number::currency($item ?? 0))
            ->column('total_pay', 'Rate Total', as: fn($item, $row) => Number::currency($item ?? 0))
            ->column(label: 'Percent of days in reporting range', as: fn($item, $row) => number_format(100 * ($row['days_in_range'] / $row['days_in_appt']), 2))
            ->column('reporting_start')
            ->column('reporting_end')
            ->column('start', 'Appointment Start')
            ->column('end', 'Appointment End')
            ->column('days_in_appt')
            ->column('days_in_range', 'Appt days in reporting range');
    }
}
