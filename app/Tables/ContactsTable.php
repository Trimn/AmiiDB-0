<?php

namespace App\Tables;

use App\Models\Contacts;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class ContactsTable extends AbstractTable
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
        return Contacts::query();
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
            ->name('contacts')
            ->withGlobalSearch(columns: ['name', 'fellowView.name'])
            ->column('name', sortable: true)
            ->column('position', sortable: true)
            ->column('email', sortable: true)
            ->column('phone', sortable: true)
            ->column('department', sortable: true)
            ->column('fellowView.name', 'Fellow', sortable: true)
            ->column('notes', sortable: true)
            ->rowSlideover(fn (Contacts $contact) => route('contacts.edit', ['contact' => $contact]))
            ->paginate()
            ->export();


            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
