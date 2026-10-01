<?php

namespace App\Tables;

use App\Models\People;
use App\Models\Student;
use App\Tables\StudentTable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class FellowStudentsAll extends StudentTable
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
        // $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
        //     $query->where(function ($query) use ($value) {
        //         Collection::wrap($value)->each(function ($value) use ($query) {
        //             $query
        //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('first_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('ccid', 'LIKE', "%{$value}%")
        //                 ->orWhere(DB::raw("CONCAT(`last_name`, ' ', `first_name`)"), 'LIKE', "%{$value}%")
        //                 ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%{$value}%")
        //                 ->orWhere('fn.name', 'LIKE', "%{$value}%")
        //                 ->orWhere('supervisor2', 'LIKE', "%{$value}%");
        //         });
        //     });
        // });

        // $students = QueryBuilder::for(People::class)
        //     ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
        //     ->leftJoin(DB::raw('(SELECT id, CONCAT(first_name, " ", last_name) name FROM people) fn'), 'fn.id', '=', 'fellows.pid')
        //     ->select('people.*', 'fn.name as supervisor_name')
        //     ->where(DB::raw('EXISTS(SELECT * FROM `student` WHERE `people`.`id` = `student`.`pid` AND `student`.`active` LIKE "ACTIVE%")'), '1')
        //     // ->where('people.supervisor', '=', $this->fellow->id)
        //     ->where(function (Builder $query) {
        //         $query->where('people.supervisor', '=', $this->fellow->id)
        //         ->orWhere('people.supervisor2', 'LIKE', $this->fellow->person->first_name . " " . $this->fellow->person->last_name);
        //     })
        //     ->defaultSort('last_name')
        //     ->allowedSorts(['last_name', 'first_name', 'email', 'uid', 'ccid', 'supervisor_name'])
        //     ->allowedFilters([$globalSearch, 'student_exists'])
        //     ->paginate(request('perPage', 15))
        //     ->withQueryString();

        $students = People::query()
            ->where(function (Builder $query) {
                $query->where('supervisor', '=', $this->fellow->id)
                ->orWhere('supervisor2', 'LIKE', $this->fellow->person->first_name . " " . $this->fellow->person->last_name);
            })
            ->whereExists(
                Student::query()
                    ->where('people.id', '=', DB::raw('`student`.`pid`'))
            );
        return $students;
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
