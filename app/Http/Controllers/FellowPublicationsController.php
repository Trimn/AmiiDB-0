<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FellowPublications;
use App\Forms\FellowPublicationsForm;
use App\Tables\FellowPublicationsTable;
use ProtoneMedia\Splade\FormBuilder\Submit;

class FellowPublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('publications.index', [
            'table' => FellowPublicationsTable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = FellowPublicationsForm::make()
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('publications.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate(FellowPublicationsForm::rules());
        $item = new FellowPublications($input);
        $item->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(FellowPublications $fellowPublications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FellowPublications $publication)
    {
        $form = FellowPublicationsForm::make()
            ->action(route('publications.update', $publication))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($publication);
        return view('publications.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FellowPublications $publication)
    {
        $input = $request->validate(FellowPublicationsForm::rules());
        $publication->fill($input);
        $publication->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FellowPublications $fellowPublications)
    {
        //
    }
}
