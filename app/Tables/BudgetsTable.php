<?php

namespace App\Tables;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class BudgetsTable extends AbstractTable
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
        return Auth::user()->can('view');
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Budget::query()->with('person');
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
            ->name('budgets')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'description', 'code', 'type', 'notes'])
            ->column(label: 'Person/Description', sortable: false, as: fn($item, $row) => 
                $row->pid 
                    ? ($row->person->first_name ?? '') . ' ' . ($row->person->last_name ?? '')
                    : ($row->description ?? '-')
            )
            ->column('start', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('end', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('amount', as: fn($item) => Number::currency($item), sortable: true)
            ->column('code', 'Speedcode', sortable: true)
            ->column('type', sortable: true)
            ->column('budget_type', 'Budget Type', sortable: true, as: fn($item) => html_entity_decode($item ?? '', ENT_QUOTES, 'UTF-8'))
            ->column('notes', sortable: true);
        
        // Conditionally add row action based on user permissions
        if (Auth::user()->can('edit')) {
            $table->rowSlideover(fn(Budget $budget) => route('budgets.edit', $budget));
        } else {
            $table->rowLink(fn(Budget $budget) => $budget->pid ? route('people.show', $budget->person) : null);
        }
        
        $table
            ->paginate(15)
            ->export();
    }
}

