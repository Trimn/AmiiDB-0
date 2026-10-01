<?php

namespace App\Tables;

use App\Models\People;
use App\Models\Status;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class StudentTable extends AbstractTable
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
        $students = People::query()->whereHas('student');
        return $students;
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
            ->name('student')
            ->defaultSort('last_name')
            ->withGlobalSearch(columns: ['last_name', 'first_name', 'supervisor_view.name', 'supervisor2', 'uid', 'ccid'])
            ->column('last_name', sortable: true)
            ->column('first_name', sortable: true)
            ->column('supervisor_view.name', 'Supervisor', sortable: true)
            ->column('supervisor2', 'Second Supervisor')
            ->column('uid', 'University ID', sortable: true)
            ->column('ccid', sortable: true)
            ->column('gender')
            ->column('citizenship')
            ->column('immigration')
            ->rowLink(function(People $person) {
                return route('people.show', $person);
            })
            ->paginate(pageName: 'students');
    }
}
