<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAppt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreStaffApptRequest;
use App\Http\Requests\UpdateStaffApptRequest;

class StaffApptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Staff $staff)
    {
        return view('staff.appt.create', [
            'rateTypes' => ['hourly' => 1, 'salary' => 0],
            'staff' => $staff
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffApptRequest $request, Staff $staff)
    {
        $input = $request->validated();
        if(!$input['hourly']) {
            $input['grade'] = null;
            $input['step'] = null;
        }
        StaffAppt::updateOrCreate([
            'staff_id' => $staff->id,
            'start' => $input['start'],
            'end' => $input['end'],
            'rate' => $input['rate'],
            'grade' => $input['grade'],
            'step' => $input['step'],
            'hours' => $input['hours'],
            'hourly' => $input['hourly'],
            'speedcode_1' => $input['speedcode_1'],
            'speedcode_2' => $input['speedcode_2'],
            'speedcode_3' => $input['speedcode_3'],
            'speedcode_1_prc' => $input['speedcode_1_prc'],
            'speedcode_2_prc' => $input['speedcode_2_prc'],
            'speedcode_3_prc' => $input['speedcode_3_prc'],
            'benefits' => $input['benefits'],
        ]);
        return redirect()->route('people.show', ['person' => $staff->pid]);
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffAppt $staffAppt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($staffAppt)
    {
        $data = StaffAppt::find($staffAppt)->toArray();
        $data['hourly'] = $data['hourly'] == 1;

        return view('staff.appt.edit', [
            'rateTypes' => ['hourly' => 1, 'salary' => 0],
            'appt' => $data,
            'appt_id' => $staffAppt
        ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffApptRequest $request, StaffAppt $appt)
    {
        $input = $request->validated();
        if(!$input['hourly']) {
            $input['grade'] = null;
            $input['step'] = null;
        }

        $appt->fill($input);
        $appt->save();
        return redirect()->route('people.show', ['person' => $appt->staff->pid]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffAppt $appt)
    {
        if(Auth::user()->can('delete')) {
            $appt->delete();
        }
        return redirect()->back();
    }
}
