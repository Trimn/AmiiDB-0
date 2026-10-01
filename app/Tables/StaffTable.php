<?php

namespace App\Tables;

use App\Models\Staff;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class StaffTable extends AbstractTable
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

        // $staff = QueryBuilder::for(People::class)
        //     ->leftJoin('fellows', 'people.supervisor', '=', 'fellows.id')
        //     ->leftJoin(DB::raw('(SELECT id, CONCAT(`first_name`, " ", `last_name`) name FROM people) fn'), 'fn.id', '=', 'fellows.pid')
        //     ->select('people.*', 'fn.name as fellow_name')
        //     ->where(DB::raw('EXISTS(SELECT * FROM staff WHERE people.id = staff.pid)'), '1')
        //     ->defaultSort('last_name')
        //     ->allowedSorts(['last_name', 'first_name', 'fellow_name', 'email', 'uid', 'ccid', 'amii_start', 'amii_end', 'supervisor_name'])
        //     ->allowedFilters([$globalSearch])
        //     ->paginate(request('perPage', 15))
        //     ->withQueryString();
        return Staff::query();
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
            ->name('staff')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'job_title', 'position.name', 'pos_subtype.name', 'person.supervisor_view.name', 'person.supervisor2', 'person.uid'])
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('person.first_name', 'First Name', sortable: true)
            ->column('person.uid', 'University ID', sortable: true)
            ->column('person.ccid', 'CCID', sortable: true)
            ->column('person.supervisor_view.name', 'Supervisor', sortable: true, as: fn($item, $row) => $row->person->_supervisor->name())
            ->column('person.supervisor2', 'Second Supervisor')
            ->column('job_title', 'Job Title', sortable: true)
            ->column('position.name', 'Position', sortable: true)
            ->column('pos_subtype.name', 'Subtype', sortable: true)
            ->column('person.gender', 'Gender')
            ->column('person.citizenship', 'Citizenship')
            ->column('person.immigration', 'Immigration')
            ->column('person.amii_start', 'Amii Start Date')
            ->column('person.amii_end', 'Amii End Date')
            ->rowLink(function(Staff $staff) {
                return route('people.show', $staff->person);
            })
            ->paginate(pageName: 'staff');
    }
}
