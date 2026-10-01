<?php

namespace App\Tables;

use App\Models\Awards;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\SpladeTable;

class PersonAwardTable extends AbstractTable
{
    private People $person; 
    
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(People $person)
    {
        $this->person = $person;
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
        return Awards::query()->where('pid', '=', $this->person->id);
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
            ->name('personAwards')
            ->withGlobalSearch(columns: ['name'])
            ->column('name', 'Name', sortable: true)
            ->column('amount', 'Amount', as: fn($item) => Number::currency($item))
            ->column('start', sortable: true)
            ->column('end', sortable: true)
            ->column('award_notification', 'Award Notification Date', sortable: true)
            ->rowSlideover(fn (Awards $award) => route('awards.edit', $award))
            ->paginate(15);
    }
}
