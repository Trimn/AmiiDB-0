<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Fellows;
use App\Forms\CreateCCAIHolder;
use App\Tables\CCAIHoldersTable;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\FormBuilder\Submit;
use App\Http\Requests\StoreCCAIHoldersRequest;
use App\Http\Requests\UpdateCCAIHoldersRequest;

class CCAIHoldersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ccai.index', [
            'data' => CCAIHoldersTable::build(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $form = CreateCCAIHolder::make()
            ->action(route('ccai.store'))
            ->fields([
                Submit::make()->label('Add'),
            ]);
        return view('ccai.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCCAIHoldersRequest $request)
    {
        $input = $request->validated();
        $person = new People([
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'uid' => $input['uid'],
            'ccid' => $input['ccid'],
            'gender' => $input['gender'],
            'email' => $input['email'],
        ]);
        $person->save();
        
        $ccai = new Fellows([
            'pid' => $person->id,
            'title' => $input['title'],
            'dept' => $input['dept'],
            'alias' => $input['alias'],
            'notes' => $input['notes'],
            'sal_chair' => 1,
        ]);
        $ccai->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Fellows $cCAIHolders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fellows $ccai)
    {
        $form = CreateCCAIHolder::make()
            ->action(route('ccai.update', $ccai))
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill(array_merge($ccai->person->toArray(), $ccai->toArray()));
        return view('ccai.edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCCAIHoldersRequest $request, Fellows $ccai)
    {
        $input = $request->validated();
        $person = $ccai->person;
        $person->fill([
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'uid' => $input['uid'],
            'ccid' => $input['ccid'],
            'gender' => $input['gender'],
            'email' => $input['email'],
        ]);
        $person->save();
        
        $ccai->fill([
            'pid' => $person->id,
            'title' => $input['title'],
            'dept' => $input['dept'],
            'alias' => $input['alias'],
            'notes' => $input['notes'],
            'sal_chair' => 1,
        ]);
        $ccai->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fellows $ccai)
    {
        if(Auth::user()->can('delete')) {
            $ccai->person->delete();
            $ccai->delete();
        }
        return redirect()->back();
    }
}
