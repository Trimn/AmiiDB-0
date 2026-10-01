<?php

namespace App\Tables;

use NumberFormatter;
use App\Models\StaffAppt;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class StaffApptTable extends AbstractTable
{
    private $staff;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($staff)
    {
        //
        $this->staff = $staff;
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

        $appts = QueryBuilder::for(StaffAppt::class)
            ->select('staff_appt.*', DB::raw("((CASE WHEN hourly = 1 THEN rate * (hours / 5) ELSE rate / IF((DAYOFYEAR(start) <= DAYOFYEAR(CONCAT(YEAR(start), '-02-29')) AND DAYOFYEAR(end) >= DAYOFYEAR(CONCAT(YEAR(start), '-02-29'))) OR (DAYOFYEAR(start) <= DAYOFYEAR(CONCAT(YEAR(end), '-02-29')) AND DAYOFYEAR(end) >= DAYOFYEAR(CONCAT(YEAR(end), '-02-29'))), 261, 260) END) * ((5 * (DATEDIFF(end, start) DIV 7) + MID('0123455401234434012332340122123401101234000123450', 7 * WEEKDAY(start) + WEEKDAY(end) + 1, 1))) + benefits) as total_pay"))
            ->where('staff_id', '=', $this->staff->id)
            ->defaultSort('-start')
            ->allowedSorts(['start', 'end', 'rate', 'grade', 'step', 'hours'])
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
            ->name('staffAppt')
            ->withGlobalSearch()
            ->column('start', sortable: true)
            ->column('end', sortable: true)
            ->column('total_pay', as: fn($p, $a) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($p, 'CAD'))
            ->column('rate', as: fn($p) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($p, 'CAD'))
            ->column('hourly', as: fn($val) => $val ? '✔️' : '❌')
            ->column('grade')
            ->column('step')
            ->column('hours')
            ->column('benefits', as: fn($p, $a) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($p, 'CAD'))
            ->column('speedcode_1')
            ->column('speedcode_1_prc', 'Speedcode 1 %')
            ->column('speedcode_2')
            ->column('speedcode_2_prc', 'Speedcode 2 %')
            ->column('speedcode_3')
            ->column('speedcode_3_prc', 'Speedcode 3 %')
            ->rowSlideover(fn (StaffAppt $appt) => route('staff.appt.edit', $appt));

            if(Auth::user()->can('delete')) {
                $table
                    ->column(label: 'Delete');
            }
    }
}
