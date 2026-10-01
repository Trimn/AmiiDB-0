<?php

namespace App\Tables;

use App\Models\NewProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class NewProjectsTable extends AbstractTable
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
        return NewProjects::query()->paginate(pageName: 'new');
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
            ->name('newProjects')
            ->column('holder')
            ->column('project_id')
            ->column('award_start')
            ->column('award_end')
            ->column('code')
            ->column('title')
            ->column('total_award', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('funds_before', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('funds_after', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('project_status')
            ->column('percent_spent')
            ->column('oe_status')
            ->column('auth_oe_amount', as: fn($item) => is_null($item) ? $item : Number::currency($item), sortable: true)
            ->column('oe_auth_end')
            ->column('oe_req_status')
            ->column(label: 'Actions');
    }
}
