<?php

namespace App\Tables;

use App\Models\Etrac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class EtracTable extends AbstractTable
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
        return Etrac::query()->paginate(pageName: 'etrac');
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
            ->name('etrac')
            ->column('account')
            ->column('fund')
            ->column('department')
            ->column('program')
            ->column('project')
            ->column('category')
            ->column('date')
            ->column('actual')
            ->column('commitment')
            ->column('journal')
            ->column('supplier')
            ->column('voucher')
            ->column('rpt_id')
            ->column('invoice')
            ->column('invoice_no')
            ->column('po_no')
            ->column('po_line');
    }
}
