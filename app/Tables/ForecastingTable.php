<?php

namespace App\Tables;

use Carbon\Carbon;
use App\Models\Etrac;
use App\Models\Salary;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ForecastingTable extends AbstractTable implements WithStrictNullComparison
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
        return $request->user()->can('view');
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        $tbl = Projects::query();
        if(!Auth::user()->can('view')) {
            $tbl->where(function($query) {
                $query->whereRelation('speedcode', 'description', 'LIKE', 'AMII CIFAR%')
                      ->orWhereRelation('speedcode', 'description', 'LIKE', 'AMII Jaremko');
            });
        }

        return $tbl;
    }

    protected ?SpladeTable $myTable = null;

    public function make(): SpladeTable
    {
        if ($this->myTable) {
            return $this->myTable;
        }

        $query = $this->for();

        $table = new class($query) extends \ProtoneMedia\Splade\SpladeQueryBuilder {
            public $ignoreProjectStatusAll = false;
            public $innerQuery;

            public function __construct($query) {
                parent::__construct($query);
                $this->innerQuery = $query;
            }

            public function filters(): \Illuminate\Support\Collection {
                $filters = parent::filters();
                
                if ($this->ignoreProjectStatusAll && $filters->has('project_status')) {
                    $filter = $filters->get('project_status');
                    if ($filter->value === 'all' || $filter->value === '') {
                        $filter->value = null;
                    }
                }
                
                return $filters;
            }
            
            public function loadResource(): self {
                $this->ignoreProjectStatusAll = true;
                
                // Add default filtering here instead of mutating request
                $filters = parent::filters();
                $statusFilter = $filters->get('project_status');
                
                if (!$statusFilter || !$statusFilter->hasValue() || $statusFilter->value === '') {
                    $this->innerQuery->where(function($q) {
                        $q->where('project_status', '!=', 'Complete')
                          ->orWhereNull('project_status');
                    });
                }
                
                parent::loadResource();
                $this->ignoreProjectStatusAll = false;
                return $this;
            }
            
            public function getBuilderForExport(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Spatie\QueryBuilder\QueryBuilder {
                $this->ignoreProjectStatusAll = true;
                $builder = parent::getBuilderForExport();
                $this->ignoreProjectStatusAll = false;
                return $builder;
            }
            
            public function performBulkAction(callable $action, array $ids) {
                $this->ignoreProjectStatusAll = true;
                parent::performBulkAction($action, $ids);
                $this->ignoreProjectStatusAll = false;
            }
        };

        return $this->myTable = tap(
            $table,
            function (SpladeTable $table) {
                $table->setConfigurator($this);
                $this->configure($table);
            }
        );
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $start = request()->query('start', Carbon::createFromFormat('Y-m-d H:s:i', (Carbon::now()->year) . '-04-01 00:00:00')->format('Y-m-d'));
        $end = request()->query('end', Carbon::createFromFormat('Y-m-d H:s:i', (Carbon::now()->year+1) . '-04-01 00:00:00')->subSeconds(2)->format('Y-m-d'));
        $fy = request()->query('fy', Carbon::now()->year);
        $statuses = Projects::statuses();
        if (isset($statuses['Complete'])) {
            unset($statuses['Complete']);
            $statuses['Complete'] = 'Complete';
        }
        $statuses['all'] = 'All (Including Complete)';

        $table
            ->name('forecasting')
            ->withGlobalSearch(columns: ['speedcode.fellowRel.person.first_name', 'speedcode.fellowRel.person.last_name', 'code', 'speedcode.combo_code', 'speedcode.project', 'speedcode.description'])
            ->selectFilter('speedcode.fellow', Fellows::optionsAll(), label: 'Fellow')
            ->selectFilter('project_status', $statuses, noFilterOptionLabel: 'Exclude Complete')
            ->selectFilter('who_assigned', FinanceTeam::options())
            ->selectFilter('priority_id', ProjectPriority::options(), 'Priority')
            ->selectFilter('eval_status_id', ProjectEvaluation::options(), 'Evaluation Status')
            ->column('speedcode.fellowRel.person.first_name', 'Holder', as: fn($item, $row) => Fellows::find($row->speedcode->fellow)->name(), sortable: true)
            ->column('speedcode.project', 'Project ID', sortable: true)
            ->column('code', 'Speed Code', sortable: true)
            ->column('speedcode.description', 'Title', sortable: true)
            ->column('speedcode.award_start', 'Award Start', sortable: true)
            ->column('speedcode.award_end', 'Award End', sortable: true)
            ->column(label: 'Days Left', as: fn($item, $row) => Carbon::now()->diffInDays(Carbon::parse($row->speedcode['award_end']), false))
            ->column(
                'total_award', 'Total Award', 
                as: fn($item) => is_null($item) ? $item : Number::currency($item), 
                exportAs: fn($item) => $item,
                sortable: true, 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                'opening_balance', 
                as: fn($item, $row) => $item ? Number::currency($item) : $item, 
                exportAs: fn($item) => $item,
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'eTRAC EXP Actuals', 
                as: fn($item, $row) => Number::currency(Etrac::actual($row['speedcode']['project'], $row['program'], $start, $end)), 
                exportAs: fn($item, $row) => Etrac::actual($row['speedcode']['project'], $row['program'], $start, $end),
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'eTRAC SAL Actuals', 
                as: fn($item, $row) => Number::currency(Salary::getProjectActuals($row['speedcode']['project'], $row['program'], $start, $end)), 
                exportAs: fn($item, $row) => Salary::getProjectActuals($row['speedcode']['project'], $row['program'], $start, $end),
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'eTRAC Commitments', 
                as: fn($item, $row) => Number::currency(Etrac::commitment($row['speedcode']['project'], $row['program'])), 
                exportAs: fn($item, $row) => Etrac::commitment($row['speedcode']['project'], $row['program']),
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                'project_view.funds_before_calc', 'Funds Available Before Commitments', 
                as: fn($item, $row) => Number::currency($row->get_funds_before()), 
                exportAs: fn($item, $row) => $row->get_funds_before(),
                sortable: true, 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'Check FABC', 
                as: function($item, $row) use ($start, $end) {
                    $res = $row->get_funds_before() - ($row['opening_balance'] - Etrac::actual($row['speedcode']['project'], $row['program'], $start, $end));
                    return is_null($res) ? $res : Number::currency($res);
                }, 
                exportAs: fn($item, $row) => $row->get_funds_before() - ($row['opening_balance'] - Etrac::actual($row['speedcode']['project'], $row['program'], $start, $end)),
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'Est Student Salary Commitments', 
                as: fn($item, $row) => Number::currency(Projects::student_commitment($row, $end)), 
                exportAs: fn($item, $row) => Projects::student_commitment($row, $end), 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'Est Staff Salary Commitments', 
                as: fn($item, $row) => Number::currency(Projects::staff_commitment($row, $end)), 
                exportAs: fn($item, $row) => Projects::staff_commitment($row, $end), 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                label: 'eTRAC Non-salary Commitments', 
                as: fn($item, $row) => Number::currency(Projects::etrac_nonsalary_commitment($row, $end)), 
                exportAs: fn($item, $row) => Projects::etrac_nonsalary_commitment($row, $end), 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                'project_view.funds_after_calc', 'Funds Available After Commitments', 
                as: fn($item, $row) => Number::currency($row->get_funds_after() ?? 0), 
                exportAs: fn($item, $row) => $row->get_funds_after(),
                sortable: true, 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD)
            // ->column(
            //     label: 'Estimated Funds Available after Commitments', 
            //     as: function($item, $row) use ($end) {
            //         $res = $row['funds_before'] - Projects::funds_left($row, $end);
            //         return is_null($res) ? $res : Number::currency($res);
            //     }, 
            //     exportAs: fn($item, $row) => $row['funds_before'] - Projects::funds_left($row, $end),
            //     exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            // )
            ->column(
                label: 'Check FAAC', 
                as: function($item, $row) use ($start, $end) {
                    $res = $row->get_funds_after() - ($row['opening_balance'] - Etrac::computed_funds($row['speedcode']['project'], $row['program'], $start, $end));
                    return is_null($res) ? $res : Number::currency($res);
                }, 
                exportAs: fn($item, $row) => $row->get_funds_after() - ($row['opening_balance'] - Etrac::computed_funds($row['speedcode']['project'], $row['program'], $start, $end)),
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column(
                'future_funding', 'Future Funding', 
                as: fn($item) => is_null($item) ? $item : Number::currency($item), 
                exportAs: fn($item) => $item,
                sortable: true, 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column('ff_start', 'FF Start Date')
            ->column('ff_end', 'FF End Date')
            // ->column('ff_verified', 'FF Verified')
            // ->column('oe_status', 'Over Expenditure Status', sortable: true)
            // ->column('project_status', 'Project Status', sortable: true)
            // ->column('percent_spent', 'Percent Spent', sortable: true)
            // ->column('auth_oe_amount', 'Authorized OE Amount', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            // ->column('oe_auth_end', 'OE Authorization End Date', sortable: true)
            // ->column('oe_req_status', 'OE Request Status', sortable: true)
            // ->column('program', 'Program')
            ->column('notes', 'Notes', sortable: true)
            ->column('assigned.name', 'Who', sortable: true)
            ->column('priority.priority', 'Priority', sortable: true)
            ->column('eval_status.status', 'Evaluation Status', sortable: true)
            ->column('project_status', 'Project Status', sortable: true)
            // ->column('financial_report', 'Financial Report', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            // ->column('supervisor_review', 'Supervisor Review', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->defaultSort('created_at')
            ->rowModal(fn(Projects $proj) => route('forecasting.edit', ['projects' => $proj, 'end' => $end]))
            ->paginate()
            ->export();
    }
}
