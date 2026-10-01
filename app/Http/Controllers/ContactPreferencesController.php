<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactPreferences;
use App\Forms\ContactPreferencesForm;
use App\Tables\ContactPreferencesTable;
use ProtoneMedia\Splade\FormBuilder\Submit;

class ContactPreferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contact.index', [
            'table' => ContactPreferencesTable::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = ContactPreferencesForm::make()
            ->action(route('contact.store'))
            ->fields([
                Submit::make()->label('Add'),
            ]);

        return view('contact.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate(ContactPreferencesForm::rules());
        $contact = new ContactPreferences($input);
        $contact->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactPreferences $contactPreferences)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactPreferences $contact)
    {
        $form = ContactPreferencesForm::make()
            ->action(route('contact.update', $contact))
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill($contact);

        return view('contact.edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactPreferences $contact)
    {
        $input = $request->validate(ContactPreferencesForm::rules());
        $contact->fill($input);
        $contact->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactPreferences $contactPreferences)
    {
        //
    }
}
