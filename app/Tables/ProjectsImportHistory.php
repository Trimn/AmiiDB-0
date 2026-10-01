<?php

namespace App\Tables;

use Illuminate\Http\Request;
use App\Models\ProjectImports;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ProjectsImportHistory extends AbstractTable
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
        return ProjectImports::query();
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
            ->name('projImports')
            ->column('created_at', 'Date')
            ->column('errors', 'Validation Errors');
    }
}
