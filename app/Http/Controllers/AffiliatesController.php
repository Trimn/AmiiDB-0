<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\People;
use App\Models\Affiliates;
use Illuminate\Http\Request;
use App\Forms\AffiliatesForm;
use App\Tables\AffiliatesTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\FormBuilder\Submit;

class AffiliatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('affiliates.index', [
            'table' => AffiliatesTable::class,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = AffiliatesForm::make()
            ->action(route('affiliates.store'))
            ->fields([
                Submit::make()->label('Add'),
            ]);
        return view('affiliates.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApptTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->validate(AffiliatesForm::rules());
            $bytes = random_bytes(15);
            $input['ccid'] = substr(bin2hex($bytes), 0, 15);
            $person = new People($input);
            $person->save();

            $input['pid'] = $person->id;
            $affiliate = new Affiliates($input);
            $affiliate->save();
        }
        catch(Exception $e) {
            if($e->getCode() == 23000) {
                Toast::danger('Duplicate ID # entered!')->autoDismiss(30);
                return redirect()->back();
            }
        }

        return redirect()->route('affiliates');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Affiliates  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function show(Affiliates $affiliate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Affiliates  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function edit(Affiliates $affiliate)
    {
        $data = array_merge($affiliate->toArray(), $affiliate->person->toArray());
        $form = AffiliatesForm::make()
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill($data)
            ->action(route('affiliates.update', $affiliate));
        return view('affiliates.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApptTypeRequest  $request
     * @param  \App\Models\Affiliates $affiliate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Affiliates $affiliate)
    {
        $input = $request->validate(AffiliatesForm::rules());
        $affiliate->person->fill($input);
        $affiliate->person->save();
        $affiliate->person->touch();
        $affiliate->fill($input);
        $affiliate->save();
        return redirect()->route('affiliates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Affiliates $affiliate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Affiliates $affiliate)
    {
        //
    }

}
