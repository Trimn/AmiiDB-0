<?php

namespace App\Tables;

use App\Models\AffiliateStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class AffiliateStaffTable extends AbstractTable
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
        return AffiliateStaff::query();
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
            ->name('affiliateStaff')
            ->withGlobalSearch(columns: ['last_name', 'first_name', 'supervisor_view.name'])
            ->column('last_name', 'Last Name', sortable: true)
            ->column('first_name', 'First Name', sortable: true)
            ->column('supervisor_view.name', 'Supervisor', sortable: true)
            ->column('supervisor2', 'Superivsor 2')
            ->column('type', sortable: true)
            ->column('start', 'Start Date', sortable: true)
            ->column('end', 'End Date', sortable: true)
            ->column('notes', 'Notes')
            ->rowSlideover(fn(AffiliateStaff $aff) => route('affiliate_staff.edit', $aff))
            ->paginate()
            ->export();
    }
}
