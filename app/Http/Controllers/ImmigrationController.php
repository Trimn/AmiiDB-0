<?php

namespace App\Http\Controllers;

use App\Models\Immigration;
use App\Http\Requests\StoreImmigrationRequest;
use App\Http\Requests\UpdateImmigrationRequest;

class ImmigrationController extends Controller
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
     * @param  \App\Http\Requests\StoreImmigrationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImmigrationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Immigration  $immigration
     * @return \Illuminate\Http\Response
     */
    public function show(Immigration $immigration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Immigration  $immigration
     * @return \Illuminate\Http\Response
     */
    public function edit(Immigration $immigration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImmigrationRequest  $request
     * @param  \App\Models\Immigration  $immigration
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImmigrationRequest $request, Immigration $immigration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Immigration  $immigration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Immigration $immigration)
    {
        //
    }
}
