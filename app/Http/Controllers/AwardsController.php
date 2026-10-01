<?php

namespace App\Http\Controllers;

use App\Models\Awards;
use App\Models\People;
use App\Forms\AwardsForm;
use App\Tables\AwardsTable;
use App\Http\Requests\StoreAwardsRequest;
use App\Http\Requests\UpdateAwardsRequest;
use ProtoneMedia\Splade\FormBuilder\Submit;

class AwardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('awards.index', [
            'table' => AwardsTable::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(People $person)
    {
        $form = AwardsForm::make()
            ->fields([
                Submit::make()->label('Add'),
            ])
            ->action(route('awards.store', ['person' => $person]));
        return view('awards.create', [
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAwardsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAwardsRequest $request, People $person)
    {
        $input = $request->validated();
        $input['pid'] = $person->id;
        Awards::updateOrCreate($input);
        return redirect()->route('people.show', [
            'person' => $person
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Awards  $awards
     * @return \Illuminate\Http\Response
     */
    public function show(Awards $awards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Awards  $awards
     * @return \Illuminate\Http\Response
     */
    public function edit(Awards $award)
    {
        $form = AwardsForm::make()
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill($award)
            ->action(route('awards.update', ['award' => $award]));
        return view('awards.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAwardsRequest  $request
     * @param  \App\Models\Awards  $awards
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAwardsRequest $request, Awards $award)
    {
        $input = $request->validated();
        $award->fill($input);
        $award->save();
        return redirect()->route('people.show', [
            'person' => $award->pid
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Awards  $awards
     * @return \Illuminate\Http\Response
     */
    public function destroy(Awards $awards)
    {
        //
    }
}
