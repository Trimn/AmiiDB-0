<?php

namespace App\Tables;

use App\Models\Budget;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ProjectBudgetsTable extends AbstractTable
{
    private $project;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Projects $project)
    {
        $this->project = $project;
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
        return Budget::query()
            ->where('code', '=', $this->project->code)
            ->with(['person', 'speedcode']);
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
            ->name('project_budgets')
            ->column(label: 'Person/Description', sortable: false, as: fn($item, $row) => 
                $row->pid 
                    ? ($row->person->first_name ?? '') . ' ' . ($row->person->last_name ?? '')
                    : ($row->description ?? '-')
            )
            ->column('start', 'Start Date', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('end', 'End Date', sortable: true, as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('amount', 'Amount', sortable: true, as: fn($item, $row) => Number::currency($item))
            ->column('code', 'Speedcode', sortable: true)
            ->column('type', 'Type', sortable: true)
            ->column('budget_type', 'Budget Type', sortable: true, as: fn($item) => html_entity_decode($item ?? '', ENT_QUOTES, 'UTF-8'))
            ->defaultSort('start', 'desc')
            ->paginate(15);
    }
}

