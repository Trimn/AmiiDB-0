<?php

namespace App\Tables;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use App\Models\Salary;

class MissingSalaryTransactionsTable extends AbstractTable
{
    protected string $uid;
    protected string $project;
    protected string $program;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(string $uid, string $project, string $program)
    {
        $this->uid = $uid;
        $this->project = $project;
        $this->program = $program;
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $request->user()->hasRole('super admin') || $request->user()->hasRole('admin');
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Salary::query()
            ->whereRaw("LPAD(uid, 7, '0') COLLATE utf8mb4_general_ci = ?", [str_pad($this->uid, 7, '0', STR_PAD_LEFT)])
            ->where('project', $this->project)
            ->whereRaw('TRIM(program) = ?', [trim($this->program)]);
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
            ->name('missingSalaryTransactions')
            ->column('posting_date', 'Posting Date', sortable: true)
            ->column('actual', 'Actual', sortable: true, as: fn($actual) => Number::currency($actual ?? 0))
            ->column('commitment', 'Commitment', sortable: true, as: fn($commitment) => Number::currency($commitment ?? 0))
            ->column('earnings_code', 'Earnings Code', sortable: true, searchable: true)
            ->column('category', 'Category', sortable: true, searchable: true)
            ->column('title', 'Title', sortable: true, searchable: true)
            ->defaultSortDesc('posting_date')
            ->paginate(15);
    }
}
