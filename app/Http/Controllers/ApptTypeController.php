<?php

namespace App\Http\Controllers;

use App\Models\ApptType;
use App\Http\Requests\StoreApptTypeRequest;
use App\Http\Requests\UpdateApptTypeRequest;

class ApptTypeController extends Controller
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
     * @param  \App\Http\Requests\StoreApptTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApptTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApptType  $apptType
     * @return \Illuminate\Http\Response
     */
    public function show(ApptType $apptType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApptType  $apptType
     * @return \Illuminate\Http\Response
     */
    public function edit(ApptType $apptType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApptTypeRequest  $request
     * @param  \App\Models\ApptType  $apptType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApptTypeRequest $request, ApptType $apptType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApptType  $apptType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApptType $apptType)
    {
        //
    }
}
