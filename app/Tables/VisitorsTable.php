<?php

namespace App\Tables;

use App\Models\Fellows;
use App\Models\Visitor;
use App\Models\FellowsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class VisitorsTable extends AbstractTable
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
        return Visitor::query();
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
            ->name('visitors')
            ->withGlobalSearch(columns: ['person.first_name', 'person.last_name', 'person._supervisor.person.first_name', 'person._supervisor.person.last_name', 'speedcode'])
            ->selectFilter('person.supervisor', Fellows::options(), 'Inviting Fellow')
            ->selectFilter('status', Visitor::statuses())
            ->column('person.first_name', 'First Name', sortable: true)
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('dob', 'Date of Birth', sortable: true)
            ->column('person.gender', 'Gender', sortable: true)
            ->column('person.email', 'Alternate Email', sortable: true)
            ->column('person.uid', 'University ID', sortable: true)
            ->column('ccid_requested', 'CCID Requested?', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('ccid_req_date', 'CCID Request Date', sortable: true)
            ->column('person.ccid', 'CCID', sortable: true)
            ->column('status', 'Status', sortable: true)
            ->column('person.supervisor_view.name', 'Inviting Fellow', as: fn ($item, $row) => $row->person->_supervisor->person->first_name . ' ' . $row->person->_supervisor->person->last_name, sortable: true)
            ->column('speedcode', 'Speedcode', sortable: true)
            ->column('fvca_category', 'FVCA Category', sortable: true)
            ->column('fvca_url', 'FVCA Link', sortable: true)
            ->column('letter_of_invitation', 'Letter of Invitation', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('airfare', 'Airfare', sortable: true)
            ->column('accomodation', 'Accomodation', sortable: true)
            ->column('arrival', 'Arrival Date', sortable: true)
            ->column('departure', 'Departure Date', sortable: true)
            ->column('on_campus', 'First day on campus', sortable: true)
            ->column('workspace', 'Workspace', sortable: true)
            ->column('uofa_funding', 'Funding from UofA', sortable: true)
            ->column('payment_amount', 'Payment amount', sortable: true)
            ->column('payment_category', 'Payment amount category', sortable: true)
            ->column('welcomed', 'Welcome Completed', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('welcome_url', 'Welcome Link', sortable: true)
            ->column('paf_completed', 'PAF completed', as: fn($item) => ($item ? '✔️' : '❌'), sortable: true)
            ->column('paf_url', 'PAF Link', sortable: true)
            ->column('notes', 'Notes', sortable: true)
            ->rowSlideover(fn (Visitor $row) => route('visitor.edit', $row))
            ->paginate();
    }
}
