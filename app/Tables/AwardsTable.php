<?php

namespace App\Tables;

use App\Models\Awards;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class AwardsTable extends AbstractTable
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
        return Awards::query();
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
            ->name('awards')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'name'])
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('person.first_name', 'First Name', sortable: true)
            ->column('name', 'Name')
            ->column('amount', as: fn($item) => Number::currency($item))
            ->column('start', sortable: true)
            ->column('end', sortable: true)
            ->column('award_notification', 'Award Notification Date', sortable: true)
            ->rowLink(fn(Awards $award) => route('people.show', $award->person))
            ->paginate(15)
            ->export();
    }
}
