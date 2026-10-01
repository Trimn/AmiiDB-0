<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTermsRequest;
use App\Http\Requests\UpdateTermsRequest;

class TermsController extends Controller
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
     * @param  \App\Http\Requests\StoreTermsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTermsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function show(Terms $terms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function edit(Terms $terms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTermsRequest  $request
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTermsRequest $request, Terms $terms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terms $terms)
    {
        //
    }

    public function find(Request $request) {
        $term = Terms::where('identifier', $request->term)->get()->first();
        if(!is_null($term)) {
            return response()->json($term->toArray());
        }
        return response()->json(array());
    }
}
