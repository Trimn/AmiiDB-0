<?php

namespace App\Tables;

use App\Models\CFS;
use NumberFormatter;
use App\Models\Fellows;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class CFSTable extends AbstractTable
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
        return CFS::query();
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
            ->name('cfs')
            ->withGlobalSearch(columns: ['name', 'fellow.person.first_name', 'fellow.person.last_name', 'speedcode'])
            ->selectFilter('status', ['active' => 'Active', 'inactive' => 'Inactive'])
            ->column('name', sortable: true)
            ->column('fid', label: 'Fellow', as: function($fid, $row) {
                $fellow = Fellows::find($fid);
                return $fellow->person->first_name . " " . $fellow->person->last_name;
            })
            ->column('speedcode')
            ->column('po')
            ->column('amount', as: fn($item, $row) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($item, 'CAD'))
            ->column('start')
            ->column('end')
            ->column('remaining', as: fn($item, $row) => (new NumberFormatter('en_CA', NumberFormatter::CURRENCY))->formatCurrency($item, 'CAD'))
            ->column('status', as: fn($item, $row) => (ucfirst($item)))
            ->column('desc')
            ->paginate()
            ->rowSlideover(function(CFS $cfs) {
                return route('cfs.edit', CFS::find($cfs->id));
            });
    }
}
