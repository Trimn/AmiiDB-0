<?php

namespace App\Tables;

use App\Models\Staff;
use App\Models\People;
use App\Tables\StaffTable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class FellowStaff extends StaffTable
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
        return Staff::query()
            ->where(function (Builder $query) {
                $query->whereRelation('person._supervisor', 'id', '=', $this->fellow->id)
                ->orWhereRelation('person', 'supervisor2', 'LIKE', $this->fellow->person->first_name . ' ' . $this->fellow->person->last_name);
            })
            ->where(function (Builder $query) {
                $query->where('active', 'LIKE', 'ACTIVE%')
                ->orWhere('active', 'LIKE', 'LOA'); // Added to only show active staff
            });
            
    }

    // /**
    //  * Configure the given SpladeTable.
    //  *
    //  * @param \ProtoneMedia\Splade\SpladeTable $table
    //  * @return void
    //  */
    // public function configure(SpladeTable $table)
    // {
    //     $table
    //         ->withGlobalSearch(columns: ['id'])
    //         ->column('id', sortable: true);

    //         // ->searchInput()
    //         // ->selectFilter()
    //         // ->withGlobalSearch()

    //         // ->bulkAction()
    //         // ->export()
    // }
}
