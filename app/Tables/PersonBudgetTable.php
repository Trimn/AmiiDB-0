<?php

namespace App\Tables;

use App\Models\Budget;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class PersonBudgetTable extends AbstractTable
{
    private $person;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(People $person)
    {
        $this->person = $person;
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return Auth::user()->can('view');
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Budget::query()->where('pid', '=', $this->person->id);
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
            ->name('personBudgets')
            ->column('start', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('end', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('amount', as: fn($item, $row) => Number::currency($item), sortable: true)
            ->column('code', 'Speedcode', sortable: true)
            ->column('type', sortable: true)
            ->column('budget_type', 'Budget Type', sortable: true, as: fn($item) => html_entity_decode($item ?? '', ENT_QUOTES, 'UTF-8'))
            ->rowSlideover(fn(Budget $budget) => route('budgets.edit', $budget))
            ->paginate()
            ->export();
    }
}


