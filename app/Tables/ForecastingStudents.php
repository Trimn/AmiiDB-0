<?php

namespace App\Tables;

use App\Models\Salary;
use App\Models\Projects;
use App\Models\StudentAppt;
use Illuminate\Http\Request;
use App\Models\ProjectImports;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ForecastingStudents extends AbstractTable
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
        // return StudentAppt::query()
        // ->select('*', DB::raw("'$this->date' AS reporting_end"), DB::raw("'$start_date' AS reporting_start"), DB::raw("WORKDAYS(end, start) AS days_in_appt"), DB::raw("WORKDAYS(IF('$this->date' < end, '$this->date', end), IF('$start_date' > start, '$start_date', start)) AS days_in_range"), DB::raw("WORKDAYS(IF('$this->date' < end, '$this->date', end), IF('$start_date' > start, '$start_date', start)) * ((IFNULL(rate_amount, 0) + IFNULL(rate_adj, 0)) / WORKDAYS(end, start)) AS remaining"))
        // ->leftJoin('rates_view', 'student_appt.rate', '=', 'rates_view.id')
        // ->where('end', '>', $start_date)
        // ->where('start', '<', $this->date)
        // ->where(function ($query) {
        //     $query->where('speedcode_1', '=', $this->project->code)->orWhere('speedcode_2', '=', $this->project->code)->orWhere('speedcode_3', '=', $this->project->code);
        // });
        return Projects::student_commitment_query($this->project, $this->date);
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
            ->name('fellows_students')
            ->column('student.person.last_name', 'Last Name', sortable: true)
            ->column('student.person.first_name', 'First Name', sortable: true)
            ->column('student.person.uid', 'UID', sortable: true)
            ->column(label: 'Actuals', as: fN($item, $row) => Number::currency(Salary::getPersonActuals($this->project->speedcode['project'], $this->project['program'], $row->student->person['uid'], $row['start'], $row['end'])))
            ->column('remaining', 'Est commitment', as: fn($item, $row) => Number::currency($item ?? 0))
            ->column('rate_amount', as: fn($item, $row) => Number::currency($item ?? 0))
            ->column('rate_adj', as: fn($item, $row) => Number::currency($item ?? 0))
            ->column(label: 'Rate Total', as: fn($item, $row) => Number::currency(($row['rate_amount'] ?? 0) + ($row['rate_adj'] ?? 0)))
            ->column(label: 'Percent of days in reporting range', as: fn($item, $row) => number_format(100 * ($row['days_in_range'] / $row['days_in_appt']), 2))
            ->column('reporting_start')
            ->column('reporting_end')
            ->column('start', 'Appointment Start')
            ->column('end', 'Appointment End')
            ->column('days_in_appt')
            ->column('days_in_range', 'Appt days in reporting range');
    }
}
