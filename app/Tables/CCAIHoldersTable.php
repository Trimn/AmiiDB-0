<?php

namespace App\Tables;

use App\Models\Fellows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class CCAIHoldersTable extends AbstractTable
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
        return Fellows::query()->where('sal_chair', 1);
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
            ->name('ccai')
            ->withGlobalSearch(columns: ['person.last_name', 'person.first_name', 'person.uid', 'person.ccid'])
            ->column('person.last_name', 'Last Name')
            ->column('person.first_name', 'First Name')
            ->column('person.uid', 'UID')
            ->column('person.ccid', 'CCID')
            ->column('title')
            ->column('dept')
            ->column('person.email', 'Alternate Email')
            ->column('alias')
            ->column('notes');

        if(Auth::user()->can('delete')) {
            $table
                ->column(label: 'Delete');
        }

        $table
            ->rowSlideover(fn (Fellows $fellow) => route('ccai.edit', $fellow))
            ->export()
            ->paginate();
    }
}
