<?php

namespace App\Http\Controllers;

use App\Models\Speedcodes;
use Illuminate\Http\Request;
use App\Forms\SpeedcodesForm;
use App\Tables\SpeedcodesTable;
use ProtoneMedia\Splade\FormBuilder\Submit;
use App\Http\Requests\StoreSpeedcodesRequest;
use App\Http\Requests\UpdateSpeedcodesRequest;

class SpeedcodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('speedcodes.index', [
            'speedcodes' => new SpeedcodesTable(true)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return view('speedcodes.index', [
            'speedcodes' => new SpeedcodesTable(false)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = SpeedcodesForm::make()
            ->fields([
                Submit::make()->label('Add'),
            ])
            ->fill(['is_cs' => true]);
        return view('speedcodes.create', [
            'form' => $form,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpeedcodesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpeedcodesRequest $request)
    {
        $input = $request->validated();

        Speedcodes::updateOrCreate([
            'code' => $input['code'],
            'fellow' => $input['fellow'],
            'description' => $input['description'],
            'project' => $input['project'],
            'combo_code' => $input['combo_code'],
            'award_start' => $input['award_start'],
            'award_end' => $input['award_end'],
            'status' => $input['status'],
            'is_cs' => $input['is_cs'],
            'notes' => $input['notes'],
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Speedcodes  $speedcodes
     * @return \Illuminate\Http\Response
     */
    public function show(Speedcodes $speedcodes)
    {
        //
    }

    public function find(Request $code) {
        $data = Speedcodes::find($code->code)->toArray();
        $data['combo_code'] = $data['combo_code'] ? str_pad($data['combo_code'], 9, '0', STR_PAD_LEFT) : $data['combo_code'];
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Speedcodes  $speedcodes
     * @return \Illuminate\Http\Response
     */
    public function edit(Speedcodes $code)
    {
        $data = $code->toArray();
        $data['combo_code'] = $data['combo_code'] ? str_pad($data['combo_code'], 9, '0', STR_PAD_LEFT) : $data['combo_code'];

        $form = SpeedcodesForm::make()
            ->fields([
                Submit::make()->label('Update'),
            ])
            ->fill($data)
            ->action(route('speedcodes.update', $code));
        return view('speedcodes.edit', [
            'code' => $code,
            'form' => $form
        ]);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpeedcodesRequest  $request
     * @param  \App\Models\Speedcodes  $speedcodes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpeedcodesRequest $request, Speedcodes $code)
    {
        $input = $request->validated();

        $code->fill($input);
        $code->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Speedcodes  $speedcodes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Speedcodes $speedcodes)
    {
        //
    }
}
