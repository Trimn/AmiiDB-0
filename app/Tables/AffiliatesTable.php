<?php

namespace App\Tables;

use App\Models\Affiliates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class AffiliatesTable extends AbstractTable
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
        return Affiliates::query();
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
            ->name('affiliates')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'title', 'affiliation'])
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('person.first_name', 'First Name', sortable: true)
            // ->column('person.gender', 'Gender')
            ->column('person.uid', 'ID')
            ->column('title', sortable: true)
            ->column('affiliation', sortable: true)
            ->column('person.email', 'Email')
            ->column('alternate_email', 'Alternate Email')
            ->column('website')
            ->column('notes')
            ->rowSlideover(fn(Affiliates $aff) => route('affiliates.edit', $aff));
    }
}
