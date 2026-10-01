<?php

namespace App\Http\Controllers;

use App\Models\SBA;
use App\Tables\SBATable;
use App\Forms\SBAForm;
use App\Http\Requests\StoreSBARequest;
use App\Http\Requests\UpdateSBARequest;
use ProtoneMedia\Splade\FormBuilder\Submit;

class SBAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sba.index', [
            'table' => SBATable::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = SBAForm::make()
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('sba.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSBARequest $request)
    {
        $input = $request->validated();
        SBA::updateOrCreate($input);

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(SBA $sba)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SBA $sba)
    {
        $form = SBAForm::make()
            ->action(route('sba.update', $sba))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill($sba);
        return view('sba.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSBARequest $request, SBA $sba)
    {
        $input = $request->validated();
        $sba->fill($input);
        $sba->save();

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SBA $sBA)
    {
        //
    }
}
