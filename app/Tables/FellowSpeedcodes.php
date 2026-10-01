<?php

namespace App\Tables;

use App\Models\Projects;
use App\Models\Speedcodes;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FellowSpeedcodes extends AbstractTable
{
    private $fellow;
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($fellow)
    {
        $this->fellow = $fellow;
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
        return Speedcodes::query()->where('fellow', '=', $this->fellow->id)->whereHas('projectModel', function (Builder $query) {
            $query->whereNot('project_status', 'LIKE', 'Complete');
        });
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $end = '2030-01-01';
        $table
            ->name('fellow_speedcodes')
            ->withGlobalSearch(columns: ['code', 'description', 'project'])
            ->column('code', sortable: true)
            ->column('description')
            ->column('project', sortable: true)
            ->column('award_start', 'Award Start', sortable: true, as: function ($item, $row) {
                if($row['projectViewModel'] && $row['projectModel']['fy_override']) {
                    return $row['projectViewModel']['curr_fy_start'];
                }
                else {
                    return $item;
                }
            })
            ->column('award_end', 'Award End', sortable: true, as: function ($item, $row) {
                if($row['projectViewModel'] && $row['projectModel']['fy_override']) {
                    return $row['projectViewModel']['curr_fy_end'];
                }
                else {
                    return $item;
                }
            })
            ->column('projectViewModel.funds_before_calc', 'Funds Available Before Commitments', sortable: true, as: fn($item, $row) => Number::currency($row['projectModel'] ? $row['projectModel']->get_funds_before() : 0))
            ->column(label: 'Est Student Salary Commitments', as: fn($item, $row) => Number::currency(Projects::student_commitment($row['projectModel'], $end)))
            ->column(label: 'Est Staff Salary Commitments', as: fn($item, $row) => Number::currency(Projects::staff_commitment($row['projectModel'], $end)))
            ->column(
                label: 'eTRAC Non-salary Commitments', 
                as: fn($item, $row) => Number::currency(Projects::etrac_nonsalary_commitment($row['projectModel'], $end)), 
                exportAs: fn($item, $row) => Projects::etrac_nonsalary_commitment($row['projectModel'], $end), 
                exportFormat: NumberFormat::FORMAT_CURRENCY_USD
            )
            ->column('projectViewModel.funds_after_calc', 'Funds Avail After Commitments', sortable: true, as: fn($item, $row) => Number::currency($row['projectModel'] ? $row['projectModel']->get_funds_after() : 0))
            ->column('projectModel.future_funding', 'Future Funding', sortable: true, as: fn($item, $row) => $item ? Number::currency($item) : $item)
            ->column('projectModel.notes', 'Notes')
            ->rowModal(fn(Speedcodes $sc) => route('fellowsView.viewSpeedcode', ['project' => $sc['projectModel'] ?? 'none']))
            ->paginate();

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
