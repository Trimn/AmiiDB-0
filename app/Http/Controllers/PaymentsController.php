<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Payments;
use App\Forms\PaymentsForm;
use Illuminate\Http\Request;
use App\Tables\AllPaymentsTable;
use App\Forms\PeoplePaymentsForm;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePaymentsRequest;
use ProtoneMedia\Splade\FormBuilder\Submit;
use App\Http\Requests\UpdatePaymentsRequest;


class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = AllPaymentsTable::build();
        return view('payments.index', data: [
            'table' => $table,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(People $person)
    {
        $form = PeoplePaymentsForm::make()
            ->action(route('payments.store', $person))
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('payments.create', [
            'form' => $form,
        ]);
    }

    public function createGeneric() {
        $form = PaymentsForm::make()
            ->action(route('payments.storeGeneric'))
            ->fields([
                Submit::make()->label('Add')
            ]);
        return view('payments.createGeneric', [
            'form' => $form,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, People $person)
    {
        $input = $request->validate(PeoplePaymentsForm::rules());
        $input['pid'] = $person->id;
        $payment = new Payments($input);
        $payment->save();

        return redirect()->back();
    }

    public function storeGeneric(Request $request) {
        $input = $request->validate(PaymentsForm::rules());
        $person = People::where('uid', '=', $input['uid'])->first();
        if(!$person) {
            $person = new People([
                'last_name' => $input['last_name'],
                'first_name' => $input['first_name'],
                'uid' => $input['uid'],
                'ccid' => $input['ccid'],
                'supervisor' => $input['supervisor'],
                'supervisor2' => $input['supervisor2'],
            ]);
            $person->save();
        }
        return $this->store($request, $person);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function show(Payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function edit(Payments $payments)
    {
        $form = PeoplePaymentsForm::make()
            ->action(route('payments.update', $payments))
            ->fields(Auth::user()->can('edit') ? [
                Submit::make()->label('Update')
            ] : [])
            ->fill($payments);
        return view('payments.edit', [
            'form' => $form
        ]);
    }

    public function editGeneric(Payments $payments) {
        $form = PaymentsForm::make()
            ->action(route('payments.updateGeneric', $payments))
            ->fields([
                Submit::make()->label('Update')
            ])
            ->fill(array_merge($payments->person->toArray(), $payments->toArray()));
        return view('payments.editGeneric', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentsRequest  $request
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentsRequest $request, Payments $payments)
    {
        $input = $request->validate(PeoplePaymentsForm::rules());
        $payments->fill($input);
        $payments->save();

        return redirect()->back();
    }

    public function updateGeneric(Request $request, Payments $payments) {
        $input = $request->validate(PaymentsForm::rules());
        $person = People::where('uid', '=', $input['uid'])->first();
        if(!$person) {
            $person = new People([
                'last_name' => $input['last_name'],
                'first_name' => $input['first_name'],
                'uid' => $input['uid'],
                'ccid' => $input['ccid'],
                'supervisor' => $input['supervisor'],
                'supervisor2' => $input['supervisor2'],
            ]);
            $person->save();
        }
        $input['pid'] = $person->id;
        $payments->fill($input);
        $payments->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payments $payments)
    {
        if(Auth::user()->can('delete')) {
            $payments->delete();
        }
        return redirect()->back();
    }
}
