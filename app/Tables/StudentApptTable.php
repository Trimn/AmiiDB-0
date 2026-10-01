<?php

namespace App\Tables;

use Carbon\Carbon;
use NumberFormatter;
use App\Models\Staff;
use App\Tables\StaffTable;
use App\Models\StudentAppt;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class StudentApptTable extends AbstractTable
{
    private $student;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($student)
    {
        $this->student = $student;
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
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('term', 'LIKE', "%{$value}%")
                        ->orWhere('appt_type_name', 'LIKE', "%{$value}%")
                        ->orWhere('level', '=', "$value");
                });
            });
        });

        $appts = QueryBuilder::for(StudentAppt::class)
            ->join('appt_type', 'appt_type.id', '=', 'student_appt.appt_type')
            ->leftJoin('rates_view', 'student_appt.rate', '=', 'rates_view.id')
            ->select('student_appt.*', 'appt_type.name as appt_type_name', DB::raw('(IFNULL(rates_view.rate_amount,0) + IFNULL(student_appt.rate_adj,0)) as amount'), 'rates_view.rate_code as rate_code')
            ->where('sid', '=', $this->student->id)
            ->defaultSort('-start')
            ->allowedSorts(['term', 'start', 'end', 'level'])
            ->allowedFilters([$globalSearch])
            ->paginate(request('perPage', 15))
            ->withQueryString();
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
            ->name('studentAppt')
            ->withGlobalSearch()
            ->column('term', sortable: true)
            ->column('start')
            ->column('end')
            ->column('appt_type_name', 'Appt Type')
            ->column('amount', as: fn($item, $appt) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($item, 'CAD'))
            ->column('rate_adj', as: fn($item, $appt) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($item, 'CAD'))
            ->column('', 'Total/Month', as: fn($item, $appt) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency(($appt->amount) / (Carbon::parse($appt->start)->diffInMonths($appt->end) + 1), 'CAD'))
            ->column('rate_code')
            ->column('speedcode_1')
            ->column('speedcode_1_prc', 'Speedcode 1 %')
            ->column('speedcode_2')
            ->column('speedcode_2_prc', 'Speedcode 2 %')
            ->column('speedcode_3')
            ->column('speedcode_3_prc', 'Speedcode 3 %')
            ->column('eform')
            ->rowSlideover(fn (StudentAppt $appt) => route('student.appt.edit', $appt));

            if(Auth::user()->can('delete')) {
                $table
                    ->column(label: 'Delete');
            }
    }
}
