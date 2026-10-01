<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Visitor;
use App\Forms\VisitorForm;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use Carbon\Carbon;
use ProtoneMedia\Splade\FormBuilder\Submit;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('visitors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = VisitorForm::make()
            ->fields([
                Submit::make()->label('Add'),
            ])
            ->action(route('visitor.store'));

        return view('visitors.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVisitorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVisitorRequest $request)
    {
        $input = $request->validate(VisitorForm::rules());
        if(isset($input['uid'])) {
            $person = People::where('uid', '=', $input['uid'])->first();
        }
        else {
            $person = null;
        }
        if(!$person) {
            $person = new People([
                'last_name' => $input['last_name'],
                'first_name' => $input['first_name'],
                'email' => $input['email'],
                'uid' => $input['uid'],
                'ccid' => $input['ccid'],
                'gender' => $input['gender'],
                'supervisor' => $input['supervisor'],
            ]);
            $person->save();
        }
        $visitor = new Visitor([
            'pid' => $person->id,
            'dob' => $input['dob'],
            'ccid_requested' => $input['ccid_requested'],
            'ccid_req_date' => $input['ccid_req_date'],
            'status' => $input['status'],
            'speedcode' => $input['speedcode'],
            'fvca_category' => $input['fvca_category'],
            'fvca_url' => $input['fvca_url'],
            'letter_of_invitation' => $input['letter_of_invitation'],
            'airfare' => $input['airfare'],
            'accomodation' => $input['accomodation'],
            'arrival' => $input['arrival'],
            'departure' => $input['departure'],
            'on_campus' => $input['on_campus'],
            'workspace' => $input['workspace'],
            'uofa_funding' => $input['uofa_funding'],
            'payment_amount' => $input['payment_amount'],
            'payment_category' => $input['payment_category'],
            'welcomed' => $input['welcomed'],
            'welcome_url' => $input['welcome_url'],
            'paf_completed' => $input['paf_completed'],
            'paf_url' => $input['paf_url'],
            'notes' => $input['notes'],
        ]);
        $visitor->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        $form = VisitorForm::make()
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->action(route('visitor.update', $visitor))
            ->fill(array_merge($visitor->person->toArray(), $visitor->toArray()));

        return view('visitors.edit', [
            'form' => $form,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVisitorRequest  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVisitorRequest $request, Visitor $visitor)
    {
        $input = $request->validate(VisitorForm::rules());
        $person = $visitor->person;
        $person->fill([
            'last_name' => $input['last_name'],
            'first_name' => $input['first_name'],
            'email' => $input['email'],
            'uid' => $input['uid'],
            'ccid' => $input['ccid'],
            'gender' => $input['gender'],
            'supervisor' => $input['supervisor'],
        ]);
        $person->save();
        $visitor->fill([
            'dob' => $input['dob'],
            'ccid_requested' => $input['ccid_requested'],
            'ccid_req_date' => $input['ccid_req_date'],
            'status' => $input['status'],
            'speedcode' => $input['speedcode'],
            'fvca_category' => $input['fvca_category'],
            'fvca_url' => $input['fvca_url'],
            'letter_of_invitation' => $input['letter_of_invitation'],
            'airfare' => $input['airfare'],
            'accomodation' => $input['accomodation'],
            'arrival' => $input['arrival'],
            'departure' => $input['departure'],
            'on_campus' => $input['on_campus'],
            'workspace' => $input['workspace'],
            'uofa_funding' => $input['uofa_funding'],
            'payment_amount' => $input['payment_amount'],
            'payment_category' => $input['payment_category'],
            'welcomed' => $input['welcomed'],
            'welcome_url' => $input['welcome_url'],
            'paf_completed' => $input['paf_completed'],
            'paf_url' => $input['paf_url'],
            'notes' => $input['notes'],
        ]);
        $visitor->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {
        //
    }
}
