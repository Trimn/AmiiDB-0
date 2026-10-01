<?php

namespace App\Tables;

use App\Models\SBA;
use NumberFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class SBATable extends AbstractTable
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
        // $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
        //     $query->where(function ($query) use ($value) {
        //         Collection::wrap($value)->each(function ($value) use ($query) {
        //             $query
        //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('first_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('ccid', 'LIKE', "%{$value}%")
        //                 ->orWhere(DB::raw("CONCAT(`last_name`, ' ', `first_name`)"), 'LIKE', "%{$value}%")
        //                 ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%{$value}%")
        //                 ->orWhere('budget_holder', 'LIKE', "%{$value}%")
        //                 ->orWhere('debit_speedcode', 'LIKE', "%{$value}%")
        //                 ->orWhere('credit_speedcode', 'LIKE', "%{$value}%");
        //         });
        //     });
        // });

        // $sba = QueryBuilder::for(SBA::class)
        //     ->join('people', 'sba.pid', '=', 'people.id')
        //     ->select('sba.*', 'people.last_name', 'people.first_name')
        //     ->defaultSort('-date')
        //     ->allowedFilters([$globalSearch])
        //     ->allowedSorts(['date', 'first_name', 'debit_speedcode', 'credit_speedcode', 'budget_holder'])
        //     ->paginate(request('perPage', 15))
        //     ->withQueryString();

        $sba = SBA::query();
        return $sba;
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
            ->name('sba')
            ->withGlobalSearch(columns: ['person.first_name', 'person.last_name', 'person.ccid', 'budget_holder', 'debit_speedcode', 'credit_speedcode'])
            ->column('date', sortable: true)
            ->column('person.first_name', 'Name', sortable: true, as: fn($item, $row) => $row->person->first_name . ' ' . $row->person->last_name)
            // ->column('reason_code')
            ->column('reason')
            ->column('debit_speedcode', sortable: true)
            ->column('credit_speedcode', sortable: true)
            ->column('amount', as: fn($item, $row) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($item, 'CAD'))
            ->column('budget_holder', sortable: true)
            ->column('notes')
            ->paginate()
            ->rowSlideover(function(SBA $sba) {
                return route('sba.edit', $sba);
            });

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
