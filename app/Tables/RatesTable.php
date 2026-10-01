<?php

namespace App\Tables;

use App\Models\Immigration;
use App\Models\Rates;
use App\Models\Terms;
use App\Models\Program;
use App\Models\RatesView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class RatesTable extends AbstractTable
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
        return RatesView::query();
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
            ->name('rates')
            ->withGlobalSearch(columns: ['term', 'program', 'rate_code', 'rate_type', 'immigration'])
            ->selectFilter('term', Terms::options())
            ->selectFilter('program', Program::options())
            ->selectFilter('rate_type', Rates::options())
            ->selectFilter('immigration', Immigration::options())
            ->column('term', sortable: true)
            ->column('program', sortable: true)
            ->column('program_year', sortable: true)
            ->column('salary_step', sortable: true)
            ->column('rate_type', sortable: true)
            ->column('immigration', sortable: true)
            ->column('cs_award', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('cs_salary', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('amii_topup', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('int_idf', 'International IDF', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('int_amii', 'International Amii', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('rate_amount', sortable: true, as: fn($item, $rate) => sprintf('$%.02f', $item))
            ->column('rate_code', sortable: true)
            ->column('notes')
            ->rowSlideover(function ($item) {
                return route('reference.edit', ['table' => 'rates', 'item' => $item->id]);
            })
            ->paginate()
            ->export();
    }
}
