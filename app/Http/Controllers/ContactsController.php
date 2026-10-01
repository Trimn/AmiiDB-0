<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Forms\ContactsForm;
use App\Tables\ContactsTable;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\FormBuilder\Submit;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contacts.index', [
            'table' => ContactsTable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = ContactsForm::make()
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('contacts.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate(ContactsForm::rules());
        $contact = new Contacts($input);
        $contact->save();
        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacts $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacts $contact)
    {
        $form = ContactsForm::make()
            ->action(route('contacts.update', $contact))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($contact);
        return view('contacts.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacts $contact)
    {
        $input = $request->validate(ContactsForm::rules());
        $contact->fill($input);
        $contact->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacts $contact)
    {
        $contact->delete();
    }
}
