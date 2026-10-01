<?php

namespace App\Http\Controllers;

use App\Models\StaffType;
use App\Http\Requests\StoreStaffTypeRequest;
use App\Http\Requests\UpdateStaffTypeRequest;

class StaffTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStaffTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function show(StaffType $staffType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffType $staffType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStaffTypeRequest  $request
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffTypeRequest $request, StaffType $staffType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffType $staffType)
    {
        //
    }
}
