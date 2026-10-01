<?php

namespace App\Http\Controllers;

use App\Models\Supervisors;
use App\Http\Requests\StoreSupervisorsRequest;
use App\Http\Requests\UpdateSupervisorsRequest;

class SupervisorsController extends Controller
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
     * @param  \App\Http\Requests\StoreSupervisorsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupervisorsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supervisors  $supervisors
     * @return \Illuminate\Http\Response
     */
    public function show(Supervisors $supervisors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supervisors  $supervisors
     * @return \Illuminate\Http\Response
     */
    public function edit(Supervisors $supervisors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupervisorsRequest  $request
     * @param  \App\Models\Supervisors  $supervisors
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupervisorsRequest $request, Supervisors $supervisors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supervisors  $supervisors
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supervisors $supervisors)
    {
        //
    }
}
