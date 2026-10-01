<?php

namespace App\Tables;

use App\Models\Fellows;
use Illuminate\Http\Request;
use App\Models\FellowPublications;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class FellowPublicationsTable extends AbstractTable
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
        return FellowPublications::query();
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
            ->withGlobalSearch(columns: ['fellowsView.name', 'authors', 'title', 'pub_name', 'conf_name'])
            ->selectFilter('fid', Fellows::options(), 'Fellow')
            ->column('fellowsView.name', 'Fellow', sortable: true)
            ->column('authors', 'Additional Authors', sortable: true)
            ->column('title', sortable: true)
            ->column('pub_name', 'Publication', sortable: true)
            ->column('pub_date', 'Date', sortable: true)
            ->column('conf_name', 'Conference', sortable: true)
            ->column('url', 'URL', sortable: true)
            ->column('notes')
            ->rowSlideover(fn (FellowPublications $pub) => route('publications.edit', $pub))
            ->export();
    }
}
