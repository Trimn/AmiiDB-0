<?php

namespace App\Tables;

use Illuminate\Http\Request;
use App\Models\ContactPreferences;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;

class ContactPreferencesTable extends AbstractTable
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
        return ContactPreferences::query();
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
            ->name('contactPrefs')
            ->column('person.last_name', 'Last Name', sortable: true)
            ->column('person.first_name', 'First Name', sortable: true)
            ->column('alternate_email', 'Alternate Email')
            ->column('contact_method', 'Preferred Contact Method')
            ->column('meeting_email', 'Meeting Email')
            ->column('calendar_url', 'Additional Calendar URL')
            ->column('work_schedule', 'Work Schedule')
            ->column('doc_pref', 'Document Signing Preference')
            ->column('signature_url', 'Digital Signature URL')
            ->column('assistant_name', 'Assistant Name')
            ->column('assistant_email', 'Assistant Email')
            ->column('notes', 'Notes')
            ->rowSlideover(fn(ContactPreferences $contact) => route('contact.edit', $contact));

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()

            // ->bulkAction()
            // ->export()
    }
}
