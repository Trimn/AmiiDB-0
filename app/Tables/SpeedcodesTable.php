<?php

namespace App\Tables;

use App\Models\Fellows;
use App\Models\Speedcode;
use App\Models\Speedcodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class SpeedcodesTable extends AbstractTable
{
    private $is_cs = true;
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($is_cs = null)
    {
        if(!is_null($is_cs)) {
            $this->is_cs = $is_cs;
        }
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
        $sc = Speedcodes::query();
        if($this->is_cs) {
            $sc->where('is_cs', '=', true);
        }
        return $sc;
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
            ->name('speedcodes')
            ->selectFilter('status', Speedcodes::statusOptions())
            ->selectFilter('fellow', Fellows::options())
            ->withGlobalSearch(columns: ['code', 'description', 'project', 'combo_code', 'status'])
            ->column('code', sortable: true)
            ->column('description', sortable: true)
            ->column('project', sortable: true)
            ->column('combo_code', sortable: true, as: fn ($combo_code, $speedcode) => $combo_code ? str_pad($combo_code, 9, "0", STR_PAD_LEFT) : $combo_code)
            ->column('fellowRel.person.first_name', 'Fellow', sortable: true, as: fn($item, $row) => $row->fellowRel->person->first_name . " " . $row->fellowRel->person->last_name)
            ->column('status', sortable: true)
            ->column('notes')
            ->rowSlideover(function (Speedcodes $code) {
                return route('speedcodes.edit', $code->code);
            })
            ->paginate(15)
            ->export();
    }
}
