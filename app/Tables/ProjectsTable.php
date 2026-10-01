<?php

namespace App\Tables;

use Carbon\Carbon;
use App\Models\Etrac;
use App\Models\Fellows;
use App\Models\Projects;
use App\Models\FinanceTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Models\ProjectPriority;
use App\Models\ProjectEvaluation;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\FormBuilder\Select;

class ProjectsTable extends AbstractTable
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
        $tbl = Projects::query()->whereNot('project_status', 'LIKE', 'Complete');
        
        if(!Auth::user()->can('view')) {
            $tbl->where(function($query) {
                $query->whereRelation('speedcode', 'description', 'LIKE', 'AMII CIFAR%')
                      ->orWhereRelation('speedcode', 'description', 'LIKE', 'AMII Jaremko');
            });
        }
        
        return $tbl;
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
            ->name('projects')
            ->withGlobalSearch(columns: ['speedcode.fellowRel.person.first_name', 'speedcode.fellowRel.person.last_name', 'code', 'speedcode.combo_code', 'speedcode.project', 'speedcode.description'])
            ->selectFilter('speedcode.fellow', Fellows::optionsAll(), label: 'Fellow')
            ->selectFilter('project_status', Projects::statuses())
            ->selectFilter('balance_alert', Projects::balanceAlertOptions(), 'Project Balance Alerts')
            ->selectFilter('who_assigned', FinanceTeam::options())
            ->selectFilter('priority_id', ProjectPriority::options(), 'Priority')
            ->selectFilter('eval_status_id', ProjectEvaluation::options(), 'Evaluation Status')
            ->column('speedcode.fellowRel.person.first_name', 'Holder', as: fn($item, $row) => Fellows::find($row->speedcode->fellow)->name(), sortable: true)
            ->column('speedcode.project', 'Project ID', sortable: true)
            ->column('code', 'Speed Code', sortable: true)
            ->column('speedcode.combo_code', 'Combo Code', as: fn ($combo_code, $speedcode) => $combo_code ? str_pad($combo_code, 9, "0", STR_PAD_LEFT) : $combo_code, sortable: true)
            ->column('speedcode.description', 'Title', sortable: true)
            ->column('speedcode.award_start', 'Award Start', sortable: true)
            ->column('speedcode.award_end', 'Award End', sortable: true)
            ->column(label: 'Days Left', as: fn($item, $row) => Carbon::now()->diffInDays(Carbon::parse($row->speedcode['award_end']), false))
            ->column('total_award', 'Total Award', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('project_view.funds_before_calc', 'Funds Available Before Commitments', as: fn($item, $row) => Number::currency($row->get_funds_before()), sortable: true)
            ->column('project_view.funds_after_calc', 'Funds Available After Commitments', as: fn($item, $row) => Number::currency($row->get_funds_after()), sortable: true)
            ->column('future_funding', 'Future Funding', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('ff_start', 'FF Start Date')
            ->column('ff_end', 'FF End Date')
            ->column('ff_verified', 'FF Verified')
            // ->column('opening_balance', as: fn($item, $row) => $item ? Number::currency($item) : $item)
            // ->column(label: 'Actuals', as: fn($item, $row) => Etrac::actual($row['speedcode']['project'], $row['project']))
            // ->column(label: 'Commitments', as: fn($item, $row) => Etrac::commitment($row['speedcode']['project'], $row['project']))
            ->column('oe_status', 'Over Expenditure Status', sortable: true)
            ->column('project_status', 'Project Status', sortable: true)
            ->column('percent_spent', 'Percent Spent', sortable: true)
            // ->column('auth_oe_amount', 'Authorized OE Amount', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            // ->column('oe_auth_end', 'OE Authorization End Date', sortable: true)
            // ->column('oe_req_status', 'OE Request Status', sortable: true)
            ->column('program', 'Program')
            ->column('notes', 'Notes', sortable: true)
            ->column('assigned.name', 'Who', sortable: true)
            ->column('priority.priority', 'Priority', sortable: true)
            ->column('eval_status.status', 'Evaluation Status', sortable: true)
            ->column('balance_alert', 'Project Balance Alerts', sortable: true)
            // ->column('financial_report', 'Financial Report', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            // ->column('supervisor_review', 'Supervisor Review', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->defaultSort('created_at')
            ->rowSlideover(fn(Projects $proj) => route('projects.edit', $proj))
            ->paginate(null, 'projects')
            ->export();
    }
}
