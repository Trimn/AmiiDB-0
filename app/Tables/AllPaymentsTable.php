<?php

namespace App\Tables;

use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class AllPaymentsTable extends AbstractTable
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
        return Payments::query();
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
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'person.uid', 'person.ccid', 'person.supervisor_view.name', 'person.supervisor2', 'name', 'code'])
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('person.first_name', 'First Name', sortable: true)
            ->column('person.uid', 'UID', sortable: true)
            ->column('person.ccid', 'CCID', sortable: true)
            ->column('person.supervisor_view.name', 'Supervisor', sortable: true)
            ->column('person.supervisor2', 'Supervisor 2', sortable: true)
            ->column('name')
            ->column('start', as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('end', as: fn($item, $row) => $item->format('Y-m-d'))
            ->column('code')
            ->column('amount', as: fn($item, $row) => Number::currency($item))
            ->column('notes')
            ->rowSlideover(fn(Payments $payments) => route('payments.editGeneric', $payments))
            ->paginate()
            ->export();
    }
}
