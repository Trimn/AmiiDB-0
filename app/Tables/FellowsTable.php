<?php

namespace App\Tables;

use App\Models\People;
use App\Models\Fellows;
use App\Models\Immigration;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use Spatie\QueryBuilder\AllowedFilter;

class FellowsTable extends AbstractTable
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
        //                 ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%{$value}%");
        //         });
        //     });
        // });
        
        // $people = QueryBuilder::for(Fellows::class)
        //     ->join('people', 'fellows.pid', '=', 'people.id')
        //     ->select('people.*', 'fellows.id as fid', 'fellows.report_id', 'fellows.website', 'fellows.committees', 'fellows.start', 'fellows.notes', 'fellows.title')
        //     ->defaultSort('last_name')
        //     ->allowedSorts(['last_name', 'first_name', 'email', 'uid', 'ccid', 'gender', 'citizenship'])
        //     ->allowedFilters([$globalSearch])
        //     ->paginate(request('perPage', 15))
        //     ->withQueryString();

        // return $people;
        return Fellows::query()->where('sal_chair', 0)->orWhereNull('sal_chair');
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        // $table
        //     ->defaultSort('last_name')
        //     ->withGlobalSearch()
        //     ->column('last_name', sortable: true)
        //     ->column('first_name', sortable: true)
        //     ->column('ccid', sortable: true)
        //     ->column('title')
        //     ->column('dept')
        //     ->column('report_id', 'Reports to ID', sortable: true)
        //     ->column('uid', 'Supervisor ID', sortable: true)
        //     ->column('start', 'Fellow Start Date', sortable: true)
        //     ->column('website')
        //     ->column('committees')
        //     ->column('notes')
        //     ->rowLink(function(Fellows $fellows) {
        //         return route('people.show', People::find($fellows->id));
        //     });

        $table
            ->name('fellows')
            ->defaultSort('person.last_name')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'person.uid', 'person.ccid'])
            ->column('person.last_name', label: 'Last Name', sortable: true)
            ->column('person.first_name', label: 'First Name', sortable: true)
            ->column('person.ccid', label: 'CCID', sortable: true)
            // ->column('person.gender', label: 'Gender')
            ->column('title')
            ->column('dept')
            ->column('office')
            ->column('report_id', 'Reports to ID', sortable: true)
            ->column('person.uid', 'Supervisor ID', sortable: true)
            ->column('start', 'Fellow Start Date', as: fn($item) => $item ? $item->format('Y-m-d') : null, sortable: true)
            ->column('website')
            ->column('committees')
            ->column('phone')
            ->column('pub_platform_primary', 'Main Publication Platform', sortable: true)
            ->column('pub_list_location', 'Publication List Location', sortable: true)
            ->column('ccai_chair', label: 'CCAI Chair', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('ccai_start', label: 'CCAI Start Date', as: fn($item) => $item ? $item->format('Y-m-d') : null, sortable: true)
            ->column('ccai_end', label: 'CCAI End Date', as: fn($item) => $item ? $item->format('Y-m-d') : null, sortable: true)
            ->column('notes')
            ->rowLink(function(Fellows $fellow) {
                return route('people.show', $fellow->person);
            })
            ->paginate(15)
            ->export();
    }
}
