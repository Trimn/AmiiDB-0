<?php

namespace App\Http\Controllers;

use App\Forms\CFSForm;
use App\Models\CFS;
use App\Http\Requests\StorecfsRequest;
use App\Http\Requests\UpdatecfsRequest;
use App\Tables\CFSTable;
use ProtoneMedia\Splade\FormBuilder\Submit;

class CfsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cfs.index', [
            'data' => CFSTable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = CFSForm::make()
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('cfs.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecfsRequest $request)
    {
        $input = $request->validated();
        $cfs = new CFS($input);
        $cfs->save();

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(CFS $cfs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CFS $cfs)
    {
        $form = CFSForm::make()
            ->action(route('cfs.update', $cfs))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($cfs);

        return view('cfs.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecfsRequest $request, CFS $cfs)
    {
        $input = $request->validated();
        $cfs->fill($input);
        $cfs->save();

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CFS $cfs)
    {
        //
    }
}
